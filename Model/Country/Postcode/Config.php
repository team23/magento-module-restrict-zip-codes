<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Country\Postcode;

use Magento\Framework\Config\DataInterface;
use Team23\RestrictZipCodes\Model\Spi\Country\Postcode\GetRestrictedPostCodesInterface;

/**
 * Class Config
 *
 * Fetch all configured restricted postcodes.
 *
 * @api
 * @version 1.0.0
 */
class Config implements GetRestrictedPostCodesInterface
{
    /**
     * @var DataInterface
     */
    private DataInterface $dataStorage;

    /**
     * Config constructor
     *
     * @param DataInterface $dataStorage
     */
    public function __construct(DataInterface $dataStorage)
    {
        $this->dataStorage = $dataStorage;
    }

    /**
     * @inheritDoc
     */
    public function execute(): array
    {
        return $this->dataStorage->get();
    }
}
