# Team23 Restrict Zip Codes

This extension allows you to handle configured postcodes / zip codes different for shipping methods or payment methods.

## Description

With this Magento extension you can handle some postcodes different from others. To add restricted zip codes, add a 
`restrict_zip_codes.xml` in your own module. At the moment, only German islands are included.

In the backend you can configure if the module should show a notification on postcode validation, specify the 
notification message, and you are able to prevent the user from checking out if he entered a restricted zip code. For
better User Experience it makes sense to enable notification message if you prevent the user from checking out.

Also, you can restrict the allowed payment methods if the user uses a restricted zip code. Just select the allowed 
payment methods in the multiselect element. For this you need to enable the "Restrict Payment Methods" configuration.

## Installation details

Use following commands:

```shell
bin/magento module:enable Team23_RestrictZipCodes
bin/magento setup:upgrade
```

This module does not make any changes in the database, so you can remove it any time.
For information about a module installation in Magento 2, see 
[Enable or disable modules](http://devdocs.magento.com/guides/v2.3/install-gde/install/cli/install-cli-subcommands-enable.html).

## Extensibility

Extension developers can interact with the `Team23_RestrictZipCodes` module. For more information about the Magento 
extension mechanism, see [Magento plug-ins](http://devdocs.magento.com/guides/v2.4/extension-dev-guide/plugins.html).

[The Magento dependency injection mechanism](http://devdocs.magento.com/guides/v2.4/extension-dev-guide/depend-inj.html) 
enables you to override the functionality of the `Team23_RestrictZipCodes` module.
