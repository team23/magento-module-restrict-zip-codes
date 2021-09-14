var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-rates-validator': {
                'Team23_RestrictZipCodes/js/model/shipping-rates-validator-mixin': true
            }
        }
    },
    map: {
        '*': {
            'ui/template/form/field.html':
                'Team23_RestrictZipCodes/templates/form/field.html'
        }
    }
};
