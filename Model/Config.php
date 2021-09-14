<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Team23\RestrictZipCodes\Model\Spi\Config\GetConfigurationValueInterface;

/**
 * Class Config
 *
 * Fetch all configuration values.
 *
 * @api
 * @since 1.0.0
 */
class Config implements GetConfigurationValueInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Config constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function isPreventCheckout($scopeCode): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_PREVENT_CHECKOUT,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * @inheritDoc
     */
    public function isShowNotification($scopeCode): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SHOW_NOTIFICATION,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * @inheritDoc
     */
    public function getNotificationMessage($scopeCode): string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_NOTIFICATION_MSG,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * @inheritDoc
     */
    public function isRestrictPayment($scopeCode): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_RESTRICT_PAYMENT,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );
    }

    /**
     * @inheritDoc
     */
    public function getAllowedPaymentMethods($scopeCode): array
    {
        $allowedMethods = $this->scopeConfig->getValue(
            self::XML_PATH_ALLOWED_PAYMENT_METHODS,
            ScopeInterface::SCOPE_STORES,
            $scopeCode
        );

        return explode(',', $allowedMethods);
    }
}
