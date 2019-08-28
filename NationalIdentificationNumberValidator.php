<?php

namespace Czechphp\NationalIdentificationNumberValidator;

use function array_key_exists;
use function array_merge;
use function date;
use function preg_match;
use function range;

/**
 * This validator is not compatible with year 2100 and beyond.
 *
 * Used resources:
 *
 * @link https://www.mvcr.cz/clanek/rady-a-sluzby-dokumenty-rodne-cislo.aspx
 * @link http://lorenc.info/3MA381/overeni-spravnosti-rodneho-cisla.htm
 */
final class NationalIdentificationNumberValidator
{
    public const ERROR_NONE = 0;
    public const ERROR_FORMAT = 1;
    public const ERROR_MONTH = 2;
    public const ERROR_DAY = 3;
    public const ERROR_SEQUENCE = 4;
    public const ERROR_MODULO = 5;

    /**
     * Regular expression of expected identification number format
     */
    private const REGEXP = '/^(?<year>\d{2})(?<month>\d{2})(?<day>\d{2})\/(?<sequence>\d{3})(?<modulo>\d{1})?$/';

    /**
     * Female is differentiated by +50 in month field
     */
    private const MONTH_FEMALE = 50;

    /**
     * From year 2004 there are some people with +20 in month field
     */
    private const MONTH_AFTER_2004 = 20;

    /**
     * If modulo field is not empty then whole number should be divisible completely by this value
     * and in reverse modulo field should be equal to resulting modulo from division of whole number without modulo field
     * except case where modulo is 10, then only last digit is used for modulo (the zero).
     */
    private const MODULO = 11;

    /**
     * @var int
     */
    private $currentYear;

    public function __construct(int $currentYear = null)
    {
        $this->currentYear = $currentYear ?: date('y');
    }

    public function validate(string $value) : int
    {
        // string has expected format
        if (preg_match(self::REGEXP, $value, $matches) !== 1) {
            return self::ERROR_FORMAT;
        }

        $hasModulo = array_key_exists('modulo', $matches) && $matches['modulo'] !== '';

        // range of months from 1 to 12
        $allowedMonths = array_merge(
            range(
                1,
                12
            ), // male
            range(
                1 + self::MONTH_FEMALE,
                12 + self::MONTH_FEMALE
            ) // female
        );

        // from year 2004 there can be people with +20 in their month number
        // without modulo check it would work for people born between 1904 and 19{last_two_digits_of_current_year} too
        if ($hasModulo === true && $matches['year'] >= 4 && $matches['year'] <= $this->currentYear) {
            $allowedMonths = array_merge(
                $allowedMonths,
                range(
                    1 + self::MONTH_AFTER_2004,
                    12 + self::MONTH_AFTER_2004
                ), // male
                range(
                    1 + self::MONTH_FEMALE + self::MONTH_AFTER_2004,
                    12 + self::MONTH_FEMALE + self::MONTH_AFTER_2004
                ) // female
            );
        }

        if (!in_array($matches['month'], $allowedMonths)) {
            return self::ERROR_MONTH;
        }

        // day is between 1 and 31
        if ($matches['day'] < 1 || $matches['day'] > 31) {
            return self::ERROR_DAY;
        }

        // after year 1953 everyone should have modulo
        // this validation does not work for people born since year 2000
        if ($matches['year'] > 53 && $hasModulo === false) {
            return self::ERROR_MODULO;
        }

        // if there is no modulo then sequence can be between 001 and 999
        if ($hasModulo === false && $matches['sequence'] < 1) {
            return self::ERROR_SEQUENCE;
        }

        // number's modulo should be 0
        if ($hasModulo === true) {
            $number = (int) $matches['year'] . $matches['month'] . $matches['day'] . $matches['sequence'];
            $modulo = $number % self::MODULO;

            // from year 1954 to 1985 and sometimes even after that, modulo can be 10 which results in 0 as modulo
            if ($modulo === 10) {
                $modulo = 0;
            }

            if ($modulo !== ((int) $matches['modulo'])) {
                return self::ERROR_MODULO;
            }
        }

        return self::ERROR_NONE;
    }
}
