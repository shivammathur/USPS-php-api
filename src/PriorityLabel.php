<?php

namespace USPS;

use Exception;

/**
 * Class PriorityLabel.
 */
class PriorityLabel extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'ExpressMailLabel';
    /**
     * route added so far.
     */
    protected array $fields = [];

    /**
     * Perform the API call.
     * @throws Exception
     */
    public function createLabel(): bool|string
    {
        // Add missing required
        $this->addMissingRequired();

        // Sort them
        // Hack by the only way this will work properly
        // since usps wants the tags to be in
        // a certain order
        ksort($this->fields, SORT_NUMERIC);

        // remove the \d. from the key
        foreach ($this->fields as $key => $value) {
            $newKey = str_replace('.', '', $key);
            $newKey = preg_replace('/\d+\:/', '', $newKey);
            unset($this->fields[$key]);
            $this->fields[$newKey] = $value;
        }

        return $this->doRequest();
    }

    /**
     * Return the USPS confirmation/tracking number if we have one.
     */
    public function getConfirmationNumber(): bool|string
    {
        $response = $this->getArrayResponse();
        // Check to make sure we have it
        if (isset($response[$this->getResponseApiName()]) && isset($response[$this->getResponseApiName()]['EMConfirmationNumber'])) {
            return $response[$this->getResponseApiName()]['EMConfirmationNumber'];
        }

        return false;
    }

    /**
     * Return the USPS label as a base64 encoded string.
     */
    public function getLabelContents(): bool|string
    {
        $response = $this->getArrayResponse();
        // Check to make sure we have it
        if (isset($response[$this->getResponseApiName()]) && isset($response[$this->getResponseApiName()]['EMLabel'])) {
            return $response[$this->getResponseApiName()]['EMLabel'];
        }

        return false;
    }

    /**
     * Return the USPS receipt as a base64 encoded string.
     */
    public function getReceiptContents(): bool|string
    {
        $response = $this->getArrayResponse();
        // Check to make sure we have it
        if (isset($response[$this->getResponseApiName()]) && isset($response[$this->getResponseApiName()]['EMReceipt'])) {
            return $response[$this->getResponseApiName()]['EMReceipt'];
        }

        return false;
    }

    /**
     * returns array of all fields added.
     *
     */
    public function getPostFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the from address.
     */
    public function setFromAddress(
        string $firstName,
        string $lastName,
        string $company,
        string $address,
        string $city,
        string $state,
        string $zip,
        string|null $address2 = null,
        string|null $zip4 = null,
        string|null $phone = null
    ): self {
        $this->setField(5, 'FromFirstName', $firstName);
        $this->setField(6, 'FromLastName', $lastName);
        $this->setField(7, 'FromFirm', $company);
        $this->setField(8, 'FromAddress1', $address2);
        $this->setField(9, 'FromAddress2', $address);
        $this->setField(10, 'FromCity', $city);
        $this->setField(11, 'FromState', $state);
        $this->setField(12, 'FromZip5', $zip);
        $this->setField(13, 'FromZip4', $zip4);
        $this->setField(14, 'FromPhone', $phone);

        return $this;
    }

    /**
     * Set the to address.
     */
    public function setToAddress(
        string $firstName,
        string $lastName,
        string $company,
        string $address,
        string $city,
        string $state,
        string $zip,
        string|null $address2 = null,
        string|null $zip4 = null,
        string|null $phone = null
    ): self {
        $this->setField(15, 'ToFirstName', $firstName);
        $this->setField(16, 'ToLastName', $lastName);
        $this->setField(17, 'ToFirm', $company);
        $this->setField(18, 'ToAddress1', $address2);
        $this->setField(19, 'ToAddress2', $address);
        $this->setField(20, 'ToCity', $city);
        $this->setField(21, 'ToState', $state);
        $this->setField(22, 'ToZip5', $zip);
        $this->setField(23, 'ToZip4', $zip4);
        $this->setField(24, 'ToPhone', $phone);

        return $this;
    }

    /**
     * Set package weight in ounces.
     */
    public function setWeightOunces(mixed $weight): self
    {
        $this->setField(25, 'WeightInOunces', $weight);

        return $this;
    }

    /**
     * Set any other required string make sure you set the correct position as well
     * as the position of the items matters.
     */
    public function setField(int $position, string $key, string $value): self
    {
        $this->fields[$position.':'.$key] = $value;

        return $this;
    }

    /**
     * Add missing required elements.
     */
    protected function addMissingRequired(): void
    {
        $required = [
            '1:Option'                 => '',
            '1.1:Revision'             => '2',
            '2:EMCAAccount'            => '',
            '3:EMCAPassword'           => '',
            '4:ImageParameters'        => '',
            '26:FlatRate'              => '',
            '27:SundayHolidayDelivery' => '',
            '28:StandardizeAddress'    => '',
            '29:WaiverOfSignature'     => '',
            '30:NoHoliday'             => '',
            '31:NoWeekend'             => '',
            '32:SeparateReceiptPage'   => '',
            '33:POZipCode'             => '',
            '34:FacilityType'          => 'DDU',
            '35:ImageType'             => 'PDF',
            '36:LabelDate'             => '',
            '37:CustomerRefNo'         => '',
            '38:SenderName'            => '',
            '39:SenderEMail'           => '',
            '40:RecipientName'         => '',
            '41:RecipientEMail'        => '',
            '42:HoldForManifest'       => '',
            '43:CommercialPrice'       => 'false',
            '44:InsuredAmount'         => '',
            '45:Container'             => 'FLAT RATE ENVELOPE',
            '46:Size'                  => 'REGULAR',
            '47:Width'                 => '',
            '48:Length'                => '',
            '49:Height'                => '',
            '50:Girth'                 => '',
        ];

        foreach ($required as $item => $value) {
            $explode = explode(':', $item);
            if (!isset($this->fields[$item])) {
                $this->setField($explode[0], $explode[1], $value);
            }
        }
    }
}
