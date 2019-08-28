<?php

namespace Czechphp\NationalIdentificationNumberValidator\Tests;

use Czechphp\NationalIdentificationNumberValidator\NationalIdentificationNumberValidator;
use PHPUnit\Framework\TestCase;

class NationalIdentificationNumberValidatorTest extends TestCase
{
    /**
     * @dataProvider validProvider
     *
     * @param string $value
     */
    public function testValid(string $value)
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_NONE,
            $validator->validate($value)
        );
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            ['103224/0000'], // male born 2010-12-24 with +20
            ['108224/0016'], // female born 2010-12-24 with +20
            ['901224/0006'], // male born 1990-12-24
            ['906224/0011'], // female born 1990-12-24
            ['401224/001'], // male born 1940-12-24
            ['406224/002'], // female born 1940-12-24
        ];
    }

    public function testInvalidLength()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_FORMAT,
            $validator->validate('01224/0006')
        );
    }

    public function testInvalidCharacter()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_FORMAT,
            $validator->validate('90A224/0006')
        );
    }

    public function testInvalidMonth()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_MONTH,
            $validator->validate('901524/0006')
        );
    }

    public function testPlus20InMonthInWrongYear()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_MONTH,
            $validator->validate('902124/0003')
        );
    }

    public function testPlus20InMonthInSeeminglyCorrectYearDifferentiatedByMissingModulo()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_MONTH,
            $validator->validate('052124/001')
        );
    }

    public function testDayShouldNotBeZero()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_DAY,
            $validator->validate('501200/001')
        );
    }

    public function testDayShouldNotBeGreaterThan31()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_DAY,
            $validator->validate('500132/001')
        );
    }

    public function testAfterYear53ModuloIsRequired()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_MODULO,
            $validator->validate('540101/001')
        );
    }

    public function testWithoutModuloSequenceShouldNotBeZero()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_SEQUENCE,
            $validator->validate('500101/000')
        );
    }

    public function testIncorrectModulo()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_MODULO,
            $validator->validate('540101/0008')
        );
    }

    public function testIncorrectModuloIsCorrectIfItShouldBe10()
    {
        $validator = new NationalIdentificationNumberValidator();

        $this->assertSame(
            NationalIdentificationNumberValidator::ERROR_NONE,
            $validator->validate('540101/0110')
        );
    }
}
