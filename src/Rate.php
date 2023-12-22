<?php

namespace USPS;

use Exception;

/**
 * USPS Rate calculator class
 * used to get a rate for shipping methods.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 */
class Rate extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'RateV4';
    /**
     * list of all addresses added so far
     */
    protected array $packages = [];

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function getRate(): bool|string
    {
        return $this->doRequest();
    }

    /**
     * returns array of all packages added so far.
     *
     */
    public function getPostFields(): array
    {
        return $this->packages;
    }

    /**
     * sets the type of call to perform domestic or international.
     *
     * @param $status
     */
    public function setInternationalCall($status): void
    {
        $this->apiVersion = $status === true ? 'IntlRateV2' : 'RateV4';
    }

    /**
     * Add other option for International & Insurance.
     */
    public function addExtraOption(int|string $key, int|string $value): void
    {
        $this->packages[$key][] = $value;
    }

    /**
     * Add Package to the stack.
     *
     * @param string      $id   the address unique id
     */
    public function addPackage(RatePackage $data, mixed $id = null): void
    {
        $packageId = $id ?? count($this->packages) + 1;

        $this->packages['Package'][] = array_merge(['@attributes' => ['ID' => $packageId]], $data->getPackageInfo());
    }
}
