<?php

namespace USPS;

use Exception;

/**
 * USPS Address Verify Class
 * used to verify an address is valid.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 */
class AddressVerify extends USPSBase
{
    /**
     * the api version used for this type of call
     */
    protected string $apiVersion = 'Verify';
    /**
     * revision version for including additional response fields
     */
    protected string $revision = '';
    /**
     * list of all addresses added so far
     */
    protected array $addresses = [];

    /**
     * Perform the API call to verify the address.
     * @throws Exception
     */
    public function verify(): bool|string
    {
        return $this->doRequest();
    }

    /**
     * returns array of all addresses added so far.
     */
    public function getPostFields(): array
    {
        $postFields = $this->revision === '' || $this->revision === '0' ? [] : ['Revision' => $this->revision];
        return array_merge($postFields, $this->addresses);
    }

    /**
     * Add Address to the stack.
     */
    public function addAddress(Address $data, int|null $id = null): void
    {
        $packageId = $id ?? count($this->addresses) + 1;

        $this->addresses['Address'][] = array_merge(['@attributes' => ['ID' => $packageId]], $data->getAddressInfo());
    }

    /**
     * Set the revision value
     */
    public function setRevision($value): self
    {
        $this->revision = (string)$value;

        return $this;
    }
}
