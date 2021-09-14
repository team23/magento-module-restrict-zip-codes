<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Plugin\Quote;

use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Quote\Model\Quote\Address\Rate;
use Team23\RestrictZipCodes\Model\Spi\Config\GetConfigurationValueInterface;
use Team23\RestrictZipCodes\Model\Spi\Country\Postcode\GetRestrictedPostCodesInterface;

/**
 * Class Address
 *
 * Check if shipping rates are available for this address.
 */
class Address
{
    /**
     * @var GetConfigurationValueInterface
     */
    private GetConfigurationValueInterface $getConfigurationValue;

    /**
     * @var GetRestrictedPostCodesInterface
     */
    private GetRestrictedPostCodesInterface $getRestrictedPostCodes;

    /**
     * @var ?array
     */
    private ?array $restrictedPostalCodes = null;

    /**
     * Address constructor
     *
     * @param GetConfigurationValueInterface $getConfigurationValue
     * @param GetRestrictedPostCodesInterface $getRestrictedPostCodes
     */
    public function __construct(
        GetConfigurationValueInterface $getConfigurationValue,
        GetRestrictedPostCodesInterface $getRestrictedPostCodes
    ) {
        $this->getConfigurationValue = $getConfigurationValue;
        $this->getRestrictedPostCodes = $getRestrictedPostCodes;
    }

    /**
     * Only retrieve rates which can be applied.
     *
     * @param QuoteAddress $subject
     * @param array $result
     * @return array
     */
    public function afterGetGroupedAllShippingRates(QuoteAddress $subject, $result)
    {
        if (!is_array($result)) {
            return $result;
        }
        
        if (!$this->getConfigurationValue->isPreventCheckout($subject->getQuote()->getStoreId())) {
            return $result;
        }

        $restrictedPostalCodes = $this->getRestrictedPostalCodes();
        $rates = [];

        foreach ($result as $carrier => $carrierRates) {
            /** @var Rate $rate */
            foreach ($carrierRates as $rate) {
                $countryId = $rate->getAddress()->getCountryId();
                $postalCode = $rate->getAddress()->getPostcode();

                if (isset($restrictedPostalCodes[$countryId])
                    && in_array($postalCode, $restrictedPostalCodes[$countryId])
                ) {
                    continue;
                }

                if (!isset($rates[$carrier])) {
                    $rates[$carrier] = [];
                }

                $rates[$carrier][] = $rate;
            }
        }

        return $rates;
    }

    /**
     * Retrieve restricted postal codes
     *
     * @return array
     */
    private function getRestrictedPostalCodes(): array
    {
        if ($this->restrictedPostalCodes === null) {
            $this->restrictedPostalCodes = $this->getRestrictedPostCodes->execute();
        }

        return $this->restrictedPostalCodes;
    }
}
