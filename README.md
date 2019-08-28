# Czech National identification number Validator

> Czech: Validátor rodného čísla

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
