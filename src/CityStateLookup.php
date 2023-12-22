<?php

namespace USPS;

/*
 * USPS City/State lookup
 * used to find a city/state by a zipcode lookup
 * @since 1.0
 * @author Vincent Gabriel
 */

use Exception;

class CityStateLookup extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'CityStateLookup';
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
     */
    public function getPostFields(): array
    {
        return $this->addresses;
    }

    /**
     * Add zip zip code to the stack.
     */
    public function addZipCode(string $zip5, string $zip4 = '', string|int|null $id = null): void
    {
        $packageId = $id ?? count($this->addresses) + 1;
        $zipCodes = ['Zip5' => $zip5];
        if ($zip4 !== '' && $zip4 !== '0') {
            $zipCodes['Zip4'] = $zip4;
        }
        $this->addresses['ZipCode'][] = array_merge(['@attributes' => ['ID' => $packageId]], $zipCodes);
    }
}
