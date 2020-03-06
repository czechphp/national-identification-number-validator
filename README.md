# Czech [National identification number](https://en.wikipedia.org/wiki/National_identification_number) Validator

> Czech: Validátor [rodného čísla](https://cs.wikipedia.org/wiki/Rodn%C3%A9_%C4%8D%C3%ADslo)

[![Build Status](https://travis-ci.com/czechphp/national-identification-number-validator.svg?branch=master)](https://travis-ci.com/czechphp/national-identification-number-validator)
[![codecov](https://codecov.io/gh/czechphp/national-identification-number-validator/branch/master/graph/badge.svg)](https://codecov.io/gh/czechphp/national-identification-number-validator)

## Installation

Install the latest version with

```
$ composer require czechphp/national-identification-number-validator
```

## Basic usage

```php
<?php

use Czechphp\NationalIdentificationNumberValidator\NationalIdentificationNumberValidator;

$validator = new NationalIdentificationNumberValidator();
$violation = $validator->validate('401224/001');

if ($violation === NationalIdentificationNumberValidator::ERROR_NONE) {
    // valid
}

```
