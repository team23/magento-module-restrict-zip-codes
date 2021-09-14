<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Country\Postcode\Config;

use Magento\Framework\Config\Reader\Filesystem;

/**
 * Class Reader
 *
 * Filesystem reader to read-in custom configuration files.
 */
class Reader extends Filesystem
{
    /**
     * @inheritDoc
     */
    protected $_idAttributes = [
        '/config/zip' => 'countryCode',
        'config/zip/codes/code' => 'id',
    ];
}
