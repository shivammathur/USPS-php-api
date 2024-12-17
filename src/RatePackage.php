<?php

namespace USPS;

/**
 * USPS Rate Package
 * used by the ups rate class to create packages represented as objects.
 *
 * @since  1.0
 *
 * @author Vincent Gabriel
 */
class RatePackage extends Rate
{
    /**
     * list of all packages added so far
     */
    protected array $packageInfo = [];
    /**
     * Services constants.
     */
    final public const string SERVICE_FIRST_CLASS = 'FIRST CLASS';
    final public const string SERVICE_FIRST_CLASS_COMMERCIAL = 'FIRST CLASS COMMERCIAL';
    final public const string SERVICE_FIRST_CLASS_HFP_COMMERCIAL = 'FIRST CLASS HFP COMMERCIAL';
    final public const string SERVICE_PRIORITY = 'PRIORITY';
    final public const string SERVICE_PRIORITY_COMMERCIAL = 'PRIORITY COMMERCIAL';
    final public const string SERVICE_PRIORITY_HFP_COMMERCIAL = 'PRIORITY HFP COMMERCIAL';
    final public const string SERVICE_EXPRESS = 'EXPRESS';
    final public const string SERVICE_EXPRESS_COMMERCIAL = 'EXPRESS COMMERCIAL';
    final public const string SERVICE_EXPRESS_SH = 'EXPRESS SH';
    final public const string SERVICE_EXPRESS_SH_COMMERCIAL = 'EXPRESS SH COMMERCIAL';
    final public const string SERVICE_EXPRESS_HFP = 'EXPRESS HFP';
    final public const string SERVICE_EXPRESS_HFP_COMMERCIAL = 'EXPRESS HFP COMMERCIAL';
    final public const string SERVICE_PARCEL = 'PARCEL';
    final public const string SERVICE_MEDIA = 'MEDIA';
    final public const string SERVICE_LIBRARY = 'LIBRARY';
    final public const string SERVICE_ALL = 'ALL';
    final public const string SERVICE_ONLINE = 'ONLINE';
    /**
     * First class mail type
     * required when you use one of the first class services.
     */
    final public const string MAIL_TYPE_LETTER = 'LETTER';
    final public const string MAIL_TYPE_FLAT = 'FLAT';
    final public const string MAIL_TYPE_PARCEL = 'PARCEL';
    final public const string MAIL_TYPE_POSTCARD = 'POSTCARD';
    final public const string MAIL_TYPE_PACKAGE = 'PACKAGE';
    final public const string MAIL_TYPE_PACKAGE_SERVICE = 'PACKAGE SERVICE';
    /**
     * Container constants.
     */
    final public const string CONTAINER_VARIABLE = 'VARIABLE';
    final public const string CONTAINER_FLAT_RATE_ENVELOPE = 'FLAT RATE ENVELOPE';
    final public const string CONTAINER_PADDED_FLAT_RATE_ENVELOPE = 'PADDED FLAT RATE ENVELOPE';
    final public const string CONTAINER_LEGAL_FLAT_RATE_ENVELOPE = 'LEGAL FLAT RATE ENVELOPE';
    final public const string CONTAINER_SM_FLAT_RATE_ENVELOPE = 'SM FLAT RATE ENVELOPE';
    final public const string CONTAINER_WINDOW_FLAT_RATE_ENVELOPE = 'WINDOW FLAT RATE ENVELOPE';
    final public const string CONTAINER_GIFT_CARD_FLAT_RATE_ENVELOPE = 'GIFT CARD FLAT RATE ENVELOPE';
    final public const string CONTAINER_FLAT_RATE_BOX = 'FLAT RATE BOX';
    final public const string CONTAINER_SM_FLAT_RATE_BOX = 'SM FLAT RATE BOX';
    final public const string CONTAINER_MD_FLAT_RATE_BOX = 'MD FLAT RATE BOX';
    final public const string CONTAINER_LG_FLAT_RATE_BOX = 'LG FLAT RATE BOX';
    final public const string CONTAINER_REGIONALRATEBOXA = 'REGIONALRATEBOXA';
    final public const string CONTAINER_REGIONALRATEBOXB = 'REGIONALRATEBOXB';
    final public const string CONTAINER_RECTANGULAR = 'RECTANGULAR';
    final public const string CONTAINER_NONRECTANGULAR = 'NONRECTANGULAR';
    /**
     * Size constants.
     */
    final public const string SIZE_LARGE = 'LARGE';
    final public const string SIZE_REGULAR = 'REGULAR';

    /**
     * Set the service property.
     */
    public function setService(string $value): self
    {
        return $this->setField('Service', $value);
    }

    /**
     * Set the first class mail type property.
     */
    public function setFirstClassMailType(string $value): self
    {
        return $this->setField('FirstClassMailType', $value);
    }

    /**
     * Set the zip origin property.
     */
    public function setZipOrigination(string|int $value): self
    {
        return $this->setField('ZipOrigination', $value);
    }

    /**
     * Set the zip destination property.
     */
    public function setZipDestination(string|int $value): self
    {
        return $this->setField('ZipDestination', $value);
    }

    /**
     * Set the pounds property.
     */
    public function setPounds(string|int $value): self
    {
        return $this->setField('Pounds', $value);
    }

    /**
     * Set the ounces property.
     */
    public function setOunces(string|int $value): self
    {
        return $this->setField('Ounces', $value);
    }

    /**
     * Set the container property.
     */
    public function setContainer(string|int $value): self
    {
        return $this->setField('Container', $value);
    }

    /**
     * Set the size property.
     */
    public function setSize(string|int $value): self
    {
        return $this->setField('Size', $value);
    }

    /**
     * Add an element to the stack.
     */
    public function setField(string|int $key, mixed $value): self
    {
        $this->packageInfo[ucwords($key)] = $value;

        return $this;
    }

    /**
     * Returns a list of all the info we gathered so far in the current package object.
     */
    public function getPackageInfo(): array
    {
        return $this->packageInfo;
    }
}
