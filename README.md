# PHP Coding Standard

[![Build Status](https://travis-ci.org/christopher-evans/west-php-code-sniffer.svg?branch=master)](https://travis-ci.org/christopher-evans/west-php-code-sniffer)

## Autoloading

This package is [PSR-4][] autoloadable via composer or otherwise mapping the `West\CodingStandard`
namespace to the `West/` directory.


## Dependencies

This package requires PHP 7.0 or later; dependencies of the package are
documented in the [composer.json][] file.


## Code Quality

To run the unit tests and generate a coverage report with [PHPUnit][] run
`composer install` followed by `composer test` at the command line.


## Usage

First install the package with composer:

	composer require --dev "west-php/coding-standard"
	vendor/bin/phpcs --config-set installed_paths /path/to/your/app/vendor/west-php/coding-standard

Now `phpcs` knows where to find these sniffs.  Any existing value will be overwritten by this command.
Now the standard can used:

	vendor/bin/phpcs --standard=West ./path/to/code


[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[PHPUnit]: http://phpunit.de/
[composer.json]: ./composer.json
