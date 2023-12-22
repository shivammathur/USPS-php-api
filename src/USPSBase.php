<?php

namespace USPS;

use CurlHandle;
use Exception;

/**
 * USPS Base class
 * used to perform the actual api calls.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 */
abstract class USPSBase
{
    public const LIVE_API_URL = 'https://secure.shippingapis.com/ShippingAPI.dll';
    public const TEST_API_URL = 'https://production.shippingapis.com/ShippingAPITest.dll';
    /**
     * the error code if one exists.
     */
    protected string|int $errorCode = 0;
    /**
     * the error message if one exists.
     */
    protected string $errorMessage = '';
    /**
     * the response message.
     */
    protected string|bool $response = '';
    /**
     * the headers returned from the call made.
     */
    protected array $headers = [];
    /**
     * The response represented as an array.
     */
    protected array $arrayResponse = [];
    /**
     * All the post fields we will add to the call.
     */
    protected array $postFields = [];
    /**
     * The api type we are about to call.
     */
    protected string $apiVersion = '';
    /**
     * set whether we are in a test mode or not
     */
    public static bool $testMode = false;
    /**
     * different kind of supported api calls by this wrapper
     */
    protected array $apiCodes = [
        'RateV2'                          => 'RateV2Request',
        'RateV4'                          => 'RateV4Request',
        'IntlRateV2'                      => 'IntlRateV2Request',
        'Verify'                          => 'AddressValidateRequest',
        'ZipCodeLookup'                   => 'ZipCodeLookupRequest',
        'CityStateLookup'                 => 'CityStateLookupRequest',
        'TrackV2'                         => 'TrackFieldRequest',
        'FirstClassMail'                  => 'FirstClassMailRequest',
        'SDCGetLocations'                 => 'SDCGetLocationsRequest',
        'ExpressMailLabel'                => 'ExpressMailLabelRequest',
        'PriorityMail'                    => 'PriorityMailRequest',
        'OpenDistributePriorityV2'        => 'OpenDistributePriorityV2.0Request',
        'OpenDistributePriorityV2Certify' => 'OpenDistributePriorityV2.0CertifyRequest',
        'ExpressMailIntl'                 => 'ExpressMailIntlRequest',
        'PriorityMailIntl'                => 'PriorityMailIntlRequest',
        'FirstClassMailIntl'              => 'FirstClassMailIntlRequest',
    ];
    /**
     * Default options for curl.
     */
    public static array $CURL_OPTS = [
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_FRESH_CONNECT  => 1,
        CURLOPT_PORT           => 443,
        CURLOPT_USERAGENT      => 'usps-php',
        CURLOPT_FOLLOWLOCATION => true,
    ];

    /**
     * Constructor.
     *
     * @param string $username - the usps api username
     */
    public function __construct(protected string $username = '')
    {
    }

    /**
     * set the usps api username we are going to user.
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Return the post data fields as an array.
     * @throws Exception
     */
    public function getPostData(): array
    {
        return ['API' => $this->apiVersion, 'XML' => $this->getXMLString()];
    }

    /**
     * Set the api version we are going to use.
     */
    public function setApiVersion(string $version): void
    {
        $this->apiVersion = $version;
    }

    /**
     * Set whether we are in a test mode or not.
     */
    public function setTestMode(bool $value): void
    {
        self::$testMode = $value;
    }

    /**
     * Response api name.
     */
    public function getResponseApiName(): string
    {
        return str_replace('Request', 'Response', (string) $this->apiCodes[$this->apiVersion]);
    }

    /**
     * Makes an HTTP request. This method can be overridden by subclasses if
     * developers want to do fancier things or use something other than curl to
     * make the request.
     * @throws Exception
     */
    protected function doRequest(CurlHandle|bool|null $ch = null): string|bool
    {
        if (!$ch) {
            $ch = curl_init();
        }

        $opts = self::$CURL_OPTS;
        $opts[CURLOPT_POSTFIELDS] = http_build_query($this->getPostData(), null, '&');
        $opts[CURLOPT_URL] = $this->getEndpoint();

        // Replace 443 with 80 if it's not secured
        if (!str_contains($opts[CURLOPT_URL], 'https://')) {
            $opts[CURLOPT_PORT] = 80;
        }

        // set options
        curl_setopt_array($ch, $opts);

        // execute
        $this->setResponse(curl_exec($ch));
        $this->setHeaders(curl_getinfo($ch));

        // fetch errors
        $this->setErrorCode(curl_errno($ch));
        $this->setErrorMessage(curl_error($ch));

        // Convert response to array
        $this->convertResponseToArray();

        // If it failed then set error code and message
        if ($this->isError()) {
            $arrayResponse = $this->getArrayResponse();

            // Find the error number
            $errorInfo = $this->getValueByKey($arrayResponse, 'Error');

            if ($errorInfo) {
                $this->setErrorCode($errorInfo['Number']);
                $this->setErrorMessage($errorInfo['Description']);
            }
        }

        // close
        curl_close($ch);

        return $this->getResponse();
    }

    public function getEndpoint(): string
    {
        return self::$testMode ? self::TEST_API_URL : self::LIVE_API_URL;
    }

    abstract public function getPostFields();

    /**
     * Return the xml string built that we are about to send over to the api.
     * @throws Exception
     */
    protected function getXMLString(): string|false
    {
        // Add in the defaults
        $postFields = [
            '@attributes' => ['USERID' => $this->username],
        ];

        // Add in the sub class data
        $postFields = array_merge($postFields, $this->getPostFields());

        $xml = XMLParser::createXML($this->apiCodes[$this->apiVersion], $postFields);

        return $xml->saveXML();
    }

    /**
     * Did we encounter an error?
     */
    public function isError(): bool
    {
        $headers = $this->getHeaders();
        $response = $this->getArrayResponse();
        // First make sure we got a valid response
        if ($headers['http_code'] != 200) {
            return true;
        }

        // Make sure the response does not have error in it
        if (isset($response['Error'])) {
            return true;
        }
        // Check to see if we have the Error word in the response
        // No error
        return str_contains((string) $this->getResponse(), '<Error>');
    }

    /**
     * Was the last call successful.
     */
    public function isSuccess(): bool
    {
        return !$this->isError();
    }

    /**
     * Return the response represented as string.
     * @throws Exception
     */
    public function convertResponseToArray(): array
    {
        if ($this->getResponse()) {
            $this->setArrayResponse(XML2Array::createArray($this->getResponse()));
        }

        return $this->getArrayResponse();
    }

    /**
     * Set the array response value.
     */
    public function setArrayResponse(array $value): void
    {
        $this->arrayResponse = $value;
    }

    /**
     * Return the array representation of the last response.
     */
    public function getArrayResponse(): array
    {
        return $this->arrayResponse;
    }

    /**
     * Set the response.
     */
    public function setResponse(bool|string $response = ''): self
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get the response data.
     */
    public function getResponse(): string|bool
    {
        return $this->response;
    }

    /**
     * Set the headers.
     */
    public function setHeaders(array $headers = []): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Get the headers.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the error code number.
     */
    public function setErrorCode(string|int $code = 0): self
    {
        $this->errorCode = $code;

        return $this;
    }

    /**
     * Get the error code number.
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Set the error message.
     */
    public function setErrorMessage(string $message = ''): self
    {
        $this->errorMessage = $message;

        return $this;
    }

    /**
     * Get the error code message.
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Find a key inside a multi dim. array.
     */
    protected function getValueByKey(array $array, string $key): mixed
    {
        foreach ($array as $k => $each) {
            if ($k === $key) {
                return $each;
            }

            if (is_array($each) && ($return = $this->getValueByKey($each, $key))) {
                return $return;
            }
        }

        return null;
    }
}
