<?php

declare(strict_types=1);

namespace Team23\RestrictZipCodes\Model\Spi\Country\Postcode;

/**
 * Interface GetRestrictedPostCodesInterface
 *
 * Service programming interface to fetch all restricted postcodes.
 *
 * @api
 * @since 1.0.0
 */
interface GetRestrictedPostCodesInterface
{
    /**
     * Retrieve item list of restricted postcodes per country ID.
     *
     * @return array
     */
    public function execute(): array;
}
