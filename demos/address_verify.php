<?php

use USPS\Address;
use USPS\AddressVerify;

require_once('autoload.php');

// Initiate and set the username provided from usps
$verify = new AddressVerify('xxxx');

// During test mode this seems not to always work as expected
//$verify->setTestMode(true);

// Create new address object and assign the properties
// apparently the order you assign them is important so make sure
// to set them as the example below
$address = new Address();
$address->setFirmName('Apartment');
$address->setApt('100');
$address->setAddress('9200 Milliken Ave');
$address->setCity('Rancho Cucomonga');
$address->setState('CA');
$address->setZip5(91730);
$address->setZip4('');

// Add the address object to the address verify class
$verify->addAddress($address);

// Perform the request and return result
try {
    print_r($verify->verify());
} catch (Exception $e) {
    // Handle any errors
}
print_r($verify->getArrayResponse());

var_dump($verify->isError());

// See if it was successful
if ($verify->isSuccess()) {
    echo 'Done';
} else {
    echo 'Error: '.$verify->getErrorMessage();
}
