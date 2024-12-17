<?php

namespace USPS;

use Exception;

/**
 * USPS Zip code lookup by city/state
 * used to find a zip code by city/state lookup.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 */
class ZipCodeLookup extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'ZipCodeLookup';
    /**
     * list of all addresses added so far
     */
    protected array $addresses = [];

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function lookup(): bool|string
    {
        return $this->doRequest();
    }

    /**
     * returns array of all addresses added so far.
     *
     */
    public function getPostFields(): array
    {
        return $this->addresses;
    }

    /**
     * Add Address to the stack.
     */
    public function addAddress(Address $data, string|int|null $id = null): void
    {
        $packageId = $id ?? count($this->addresses) + 1;

        $this->addresses['Address'][] = array_merge(['@attributes' => ['ID' => $packageId]], $data->getAddressInfo());
    }
}
