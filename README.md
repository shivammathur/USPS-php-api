USPS PHP API
===========

This wrapper allows you to perform some basic calls to the USPS api. Some of the features currently supported are:

- Rate Calculator (Both domestic and international)
- Zip code lookup by address
- City/State lookup by zip code
- Verify address
- Create Priority Shipping Labels
- Create Open & Distribute Shipping Labels
- Create International Shipping Labels (Express, Priority, First Class)
- Service Delivery Calculator
- Confirm Tracking

Requirements
============

- PHP >= 8.4
- Configured with the dom and cURL extensions
- USPS API Username

Install
=======
`composer require shivammathur/usps-php-api`

Package
=======

https://packagist.org/packages/shivammathur/usps-php-api

Examples
=======

Please check the 'demos' directory for usage examples on the various types of api calls and actions you can perform.

Authors
=======
Vincent Gabriel <https://vadimg.com>
Shivam Mathur <https://shivammathur.com>

License
=======
This project is licensed under the [MIT License](LICENSE).
