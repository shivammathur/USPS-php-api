<?php

use USPS\TrackConfirm;

require_once('autoload.php');

// Initiate and set the username provided from usps
$tracking = new TrackConfirm('xxxx');

// During test mode this seems not to always work as expected
$tracking->setTestMode(true);

// Add the test package id to the trackconfirm lookup class
$tracking->addPackage('EJ958083578US');

// Perform the call and print out the results
try {
    print_r($tracking->getTracking());
} catch (Exception $e) {
    // Handle any errors
}
print_r($tracking->getArrayResponse());

// Check if it was completed
if ($tracking->isSuccess()) {
    echo 'Done';
} else {
    echo 'Error: '.$tracking->getErrorMessage();
}
