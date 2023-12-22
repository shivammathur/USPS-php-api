<?php

namespace USPS;

/**
 * USPS Address Class
 * used across other class to create addresses represented as objects.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 * @author Shivam Mathur
 */
class Address
{
    /**
     * @var array list of all key=>value pairs we added so far for the current address
     */
    protected array $addressInfo = [];

    /**
     * Set the address2 property.
     */
    public function setAddress(string|int $value): self
    {
        return $this->setField('Address2', $value);
    }

    /**
     * Set the address1 property usually the apt or suit number.
     */
    public function setApt(string|int $value): self
    {
        return $this->setField('Address1', $value);
    }

    /**
     * Set the city property.
     */
    public function setCity(string|int $value): self
    {
        return $this->setField('City', $value);
    }

    /**
     * Set the state property.
     */
    public function setState(string|int $value): self
    {
        return $this->setField('State', $value);
    }

    /**
     * Set the zip4 property - zip code value represented by 4 integers.
     */
    public function setZip4(string|int $value): self
    {
        return $this->setField('Zip4', $value);
    }

    /**
     * Set the zip5 property - zip code value represented by 5 integers.
     */
    public function setZip5(string|int $value): self
    {
        return $this->setField('Zip5', $value);
    }

    /**
     * Set the firmname property.
     */
    public function setFirmName(string|int $value): self
    {
        return $this->setField('FirmName', $value);
    }

    /**
     * Add an element to the stack.
     */
    public function setField(string|int $key, mixed $value): self
    {
        $this->addressInfo[ucwords($key)] = $value;

        return $this;
    }

    /**
     * Returns a list of all the info we gathered so far in the current address object.
     */
    public function getAddressInfo(): array
    {
        return $this->addressInfo;
    }
}
