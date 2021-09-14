<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Plugin\Checkout;

use Magento\Checkout\Model\DefaultConfigProvider as CheckoutConfigProvider;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\Store;
use Team23\RestrictZipCodes\Model\Spi\Config\GetConfigurationValueInterface;
use Team23\RestrictZipCodes\Model\Spi\Country\Postcode\GetRestrictedPostCodesInterface;

/**
 * Class DefaultConfigProvider
 *
 * Add restricted zip codes to checkout configuration.
 */
class DefaultConfigProvider
{
    /**
     * @var Session
     */
    private Session $checkoutSession;

    /**
     * @var GetConfigurationValueInterface
     */
    private GetConfigurationValueInterface $getConfigurationValue;

    /**
     * @var GetRestrictedPostCodesInterface
     */
    private GetRestrictedPostCodesInterface $getRestrictedPostCodes;

    /**
     * DefaultConfigProvider constructor
     *
     * @param Session $checkoutSession
     * @param GetConfigurationValueInterface $getConfigurationValue
     * @param GetRestrictedPostCodesInterface $getRestrictedPostCodes
     */
    public function __construct(
        Session $checkoutSession,
        GetConfigurationValueInterface $getConfigurationValue,
        GetRestrictedPostCodesInterface $getRestrictedPostCodes
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->getConfigurationValue = $getConfigurationValue;
        $this->getRestrictedPostCodes = $getRestrictedPostCodes;
    }

    /**
     * Add restricted zip codes to checkout configuration
     *
     * @param CheckoutConfigProvider $subject
     * @param mixed $result
     * @return mixed
     */
    public function afterGetConfig(CheckoutConfigProvider $subject, $result)
    {
        $storeId = $this->getStoreId();

        if (!isset($result['restrictedPostCodes'])) {
            $result['restrictedPostCodes'] = $this->getRestrictedPostCodes->execute();
        }

        if (!isset($result['restrictedPostCodesPreventCheckoutEnabled'])) {
            $result['restrictedPostCodesPreventCheckoutEnabled'] =
                $this->getConfigurationValue->isPreventCheckout($storeId);
        }

        if (!isset($result['restrictedPostCodesShowNotification'])) {
            $result['restrictedPostCodesShowNotification'] = $this->getConfigurationValue->isShowNotification($storeId);
        }

        if (!isset($result['restrictedPostCodesNotification'])) {
            $result['restrictedPostCodesNotification'] = $this->getConfigurationValue->getNotificationMessage($storeId);
        }

        if (!isset($result['restrictedPostCodesAllowedPaymentMethods'])) {
            $result['restrictedPostCodesAllowedPaymentMethods'] =
                $this->getConfigurationValue->getAllowedPaymentMethods($storeId);
        }

        return $result;
    }

    /**
     * Retrieve store ID
     *
     * @return int
     */
    private function getStoreId(): int
    {
        try {
            return (int)$this->checkoutSession->getQuote()->getStore()->getId();
        } catch (\Exception $e) {
            return Store::DEFAULT_STORE_ID;
        }
    }
}
