<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Country\Postcode\Config;

use Magento\Framework\Config\SchemaLocatorInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\Dir\Reader;

/**
 * Class SchemaLocator
 *
 * Locates and validates schema for XML.
 *
 * @api
 * @since 1.0.0
 */
class SchemaLocator implements SchemaLocatorInterface
{
    /**
     * @var string
     */
    private string $schema;

    /**
     * SchemaLocator constructor
     *
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->schema = $reader->getModuleDir(Dir::MODULE_ETC_DIR, 'Team23_RestrictZipCodes')
            . '/restrict_zip_codes.xsd';
    }

    /**
     * @inheritDoc
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @inheritDoc
     */
    public function getPerFileSchema()
    {
        return $this->schema;
    }
}
