<?php

use Firestarter\Forms\Validators\ValidateInteger;

class ValidateIntegerTest extends PHPUnit_Framework_TestCase
{
    public function testIgnoresEmptyFields()
    {
        $validator = new ValidateInteger();
        $this->assertTrue($validator->validate(''));
    }

    public function testRejectsNonNumbers()
    {
        $validator = new ValidateInteger();
        $this->assertFalse($validator->validate('not a number'));
    }

    public function testRejectsDecimals()
    {
        $validator = new ValidateInteger();
        $this->assertFalse($validator->validate('6.1'));
        $this->assertFalse($validator->validate('5,3'));
    }

    public function testAcceptsIntegers()
    {
        $validator = new ValidateInteger(true, true);
        $this->assertTrue($validator->validate('-1'));
        $this->assertTrue($validator->validate('0'));
        $this->assertTrue($validator->validate('1'));
        $this->assertTrue($validator->validate(PHP_INT_MAX));
        $this->assertTrue($validator->validate(PHP_INT_MIN));
    }

    public function testDeniesZeroes()
    {
        $validator = new ValidateInteger(false, true);
        $this->assertTrue($validator->validate('-1'));
        $this->assertFalse($validator->validate('0'));
        $this->assertTrue($validator->validate('1'));
    }

    public function testDeniesNegatives()
    {
        $validator = new ValidateInteger(true, false);
        $this->assertFalse($validator->validate('-1'));
        $this->assertTrue($validator->validate('0'));
        $this->assertTrue($validator->validate('1'));
    }
}