define([
    'jquery',
    'mage/utils/wrapper',
    'mage/translate'
], function (
    $,
    wrapper,
    $t
) {
    'use strict';

    return function (shippingRatesValidator) {
        shippingRatesValidator.postcodeValidation = wrapper.wrap(
            shippingRatesValidator.postcodeValidation,
            function (origFunction, postcodeElement) {
                var validationResult = origFunction(postcodeElement);
                var showNotificationMsg = window.checkoutConfig.restrictedPostCodesShowNotification;
                var countryId = $('select[name="country_id"]:visible').val(),
                    warnMessage;

                if (postcodeElement == null
                    || postcodeElement.value() == null
                    || validationResult === false
                    || showNotificationMsg === false
                ) {
                    return true;
                }

                postcodeElement.warn(null);

                var restrictedZipCodes = window.checkoutConfig.restrictedPostCodes[countryId];
                var notificationMsg = window.checkoutConfig.restrictedPostCodesNotification;

                if ($.inArray(postcodeElement.value(), restrictedZipCodes) !== -1) {
                    validationResult = false;

                    warnMessage = $t(notificationMsg);
                    postcodeElement.warn(warnMessage);
                }

                return validationResult;
            }
        );

        return shippingRatesValidator;
    };
});
