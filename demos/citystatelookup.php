<?php

use USPS\CityStateLookup;

require_once('autoload.php');

// Initiate and set the username provided from usps
$verify = new CityStateLookup('xxxx');

// During test mode this seems not to always work as expected
//$verify->setTestMode(true);

// Add the zip code we want to lookup the city and state
$verify->addZipCode('91730');

// Perform the call and print out the results
try {
    print_r($verify->lookup());
} catch (Exception $e) {
    // Handle any errors
}
print_r($verify->getArrayResponse());

// Check if it was completed
if ($verify->isSuccess()) {
    echo 'Done';
} else {
    echo 'Error: '.$verify->getErrorMessage();
}
