<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Spi\Config;

/**
 * Interface GetConfigurationValue
 *
 * Service programming interface to fetch all configuration values.
 *
 * @api
 * @since 1.0.0
 */
interface GetConfigurationValueInterface
{
    const XML_PATH_PREVENT_CHECKOUT = 'restrict_zip_codes/general/prevent';
    const XML_PATH_SHOW_NOTIFICATION = 'restrict_zip_codes/general/show_notification';
    const XML_PATH_NOTIFICATION_MSG = 'restrict_zip_codes/general/notification_msg';
    const XML_PATH_RESTRICT_PAYMENT = 'restrict_zip_codes/general/restrict_payment';
    const XML_PATH_ALLOWED_PAYMENT_METHODS = 'restrict_zip_codes/general/allowed_payment_methods';

    /**
     * Check if checkout should be prevented
     *
     * @param null|int|string $scopeCode
     * @return bool
     */
    public function isPreventCheckout($scopeCode): bool;

    /**
     * Check if notification should be shown
     *
     * @param null|int|string $scopeCode
     * @return bool
     */
    public function isShowNotification($scopeCode): bool;

    /**
     * Retrieve notification message
     *
     * @param null|int|string $scopeCode
     * @return string
     */
    public function getNotificationMessage($scopeCode): string;

    /**
     * Check if payment is restricted
     *
     * @param null|int|string $scopeCode
     * @return bool
     */
    public function isRestrictPayment($scopeCode): bool;

    /**
     * Retrieve allowed payment methods
     *
     * @param null|int|string $scopeCode
     * @return array
     */
    public function getAllowedPaymentMethods($scopeCode): array;
}
