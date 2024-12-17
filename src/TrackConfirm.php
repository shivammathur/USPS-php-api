<?php

namespace USPS;

use Exception;

/**
 * Class TrackConfirm.
 */
class TrackConfirm extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'TrackV2';
    /**
     * list of all packages added so far
     */
    protected array $packages = [];

    #[\Override]
    public function getEndpoint(): string
    {
        return self::$testMode ? 'https://production.shippingapis.com/ShippingAPITest.dll' : 'https://production.shippingapis.com/ShippingAPI.dll';
    }

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function getTracking(): bool|string
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
     * Add Package to the stack.
     */
    public function addPackage(string|int $id): void
    {
        $this->packages['TrackID'][] = ['@attributes' => ['ID' => $id]];
    }
}
