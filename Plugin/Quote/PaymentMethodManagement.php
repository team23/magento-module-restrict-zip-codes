<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Plugin\Quote;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\PaymentMethodManagement as QuotePaymentMethodManagement;
use Team23\RestrictZipCodes\Model\Spi\Config\GetConfigurationValueInterface;
use Team23\RestrictZipCodes\Model\Spi\Country\Postcode\GetRestrictedPostCodesInterface;

/**
 * Class PaymentMethodManagement
 *
 * Restrict payment methods if necessary.
 */
class PaymentMethodManagement
{
    /**
     * @var CartRepositoryInterface
     */
    private CartRepositoryInterface $quoteRepository;

    /**
     * @var GetConfigurationValueInterface
     */
    private GetConfigurationValueInterface $getConfigurationValue;

    /**
     * @var GetRestrictedPostCodesInterface
     */
    private GetRestrictedPostCodesInterface $getRestrictedPostCodes;

    /**
     * PaymentMethodManagement constructor
     *
     * @param CartRepositoryInterface $quoteRepository
     * @param GetConfigurationValueInterface $getConfigurationValue
     * @param GetRestrictedPostCodesInterface $getRestrictedPostCodes
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        GetConfigurationValueInterface $getConfigurationValue,
        GetRestrictedPostCodesInterface $getRestrictedPostCodes
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->getConfigurationValue = $getConfigurationValue;
        $this->getRestrictedPostCodes = $getRestrictedPostCodes;
    }

    /**
     * Only list allowed payment methods if necessary
     *
     * @param QuotePaymentMethodManagement $subject
     * @param \Magento\Payment\Model\MethodInterface[] $result
     * @param int $cartId
     * @return \Magento\Payment\Model\MethodInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetList(QuotePaymentMethodManagement $subject, $result, $cartId)
    {
        $quote = $this->quoteRepository->get($cartId);
        $storeId = $quote->getStoreId();

        if (!$this->getConfigurationValue->isRestrictPayment($storeId)) {
            return $result;
        }

        $allowedPaymentMethods = $this->getConfigurationValue->getAllowedPaymentMethods($storeId);
        $restrictedPostalCodes = $this->getRestrictedPostCodes->execute();

        $address = $quote->getShippingAddress();
        $countryId = $address->getCountryId();
        $postalCode = $address->getPostcode();

        if (isset($restrictedPostalCodes[$countryId])
            && in_array($postalCode, $restrictedPostalCodes[$countryId])
        ) {
            $paymentMethods = [];
            
            foreach ($result as $paymentMethod) {
                if (in_array($paymentMethod->getCode(), $allowedPaymentMethods)) {
                    $paymentMethods[] = $paymentMethod;
                }
            }

            return $paymentMethods;
        }

        return $result;
    }
}
