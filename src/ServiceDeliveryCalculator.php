<?php

namespace USPS;

use Exception;

/**
 * Class ServiceDeliveryCalculator.
 */
class ServiceDeliveryCalculator extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'SDCGetLocations';
    /**
     * route added so far.
     */
    protected array $route = [];

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function getServiceDeliveryCalculation(): bool|string
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
     * Add route.
     */
    public function addRoute(
        string|int $mail_class,
        string|int $origin_zip,
        string|int $destination_zip,
        string|null $accept_date = null,
        string|null $accept_time = null
    ): void
    {
        $route = [
            'MailClass'      => $mail_class,
            'OriginZIP'      => $origin_zip,
            'DestinationZIP' => $destination_zip,
        ];
        if ($accept_date !== null && $accept_date !== '' && $accept_date !== '0') {
            $route['AcceptDate'] = $accept_date;
        }
        if ($accept_time !== null && $accept_time !== '' && $accept_time !== '0') {
            $route['AcceptTime'] = $accept_time;
        }
        $this->route = $route;
    }
}
