<?php

namespace USPS;

use Exception;

/**
 * Class PriorityMailServiceStandards.
 */
class PriorityMailServiceStandards extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'PriorityMail';
    /**
     * route added so far.
     */
    protected array $route = [];

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function getServiceStandard(): bool|string
    {
        return $this->doRequest();
    }

    /**
     * returns array of all routes added so far.
     *
     */
    public function getPostFields(): array
    {
        return $this->route;
    }

    /**
     * Add route to the stack.
     */
    public function addRoute(string $origin_zip, string $destination_zip): void
    {
        $this->route = [
            'OriginZip'      => $origin_zip,
            'DestinationZip' => $destination_zip,
        ];
    }
}
