<?php

use Firestarter\Forms\OptionField;
use Firestarter\Forms\Validators\ValidateOption;

class ValidateOptionTest extends PHPUnit_Framework_TestCase
{
    public function testDoesNotAllowEmptySingleFields()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(false);

        $validator = new ValidateOption($field);
        $this->assertFalse($validator->validate(''));
    }

    public function testDoesNotAllowEmptyMultiFields()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(true);

        $validator = new ValidateOption($field);
        $this->assertFalse($validator->validate([]));
    }

    public function testAllowsValidSingleOptions()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(false);
        $field->setOptions(['a' => 'a', 'b' => 'b']);

        $validator = new ValidateOption($field);
        $this->assertTrue($validator->validate('a'));
    }

    public function testAllowsValidMultiOptions()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(true);
        $field->setOptions(['a' => 'a', 'b' => 'b']);

        $validator = new ValidateOption($field);
        $this->assertTrue($validator->validate(['a', 'b']));
    }

    public function testDisallowsValidSingleOptions()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(false);
        $field->setOptions(['a' => 'a', 'b' => 'b']);

        $validator = new ValidateOption($field);
        $this->assertFalse($validator->validate('c'));
    }

    public function testDisallowsValidMultiOptions()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(true);
        $field->setOptions(['a' => 'a', 'b' => 'b']);

        $validator = new ValidateOption($field);
        $this->assertFalse($validator->validate(['a', 'b', 'c']));
    }

    public function testDisallowsNonArraysForMultiOptions()
    {
        $field = new OptionField('test', 'select');
        $field->setAllowMultipile(true);
        $field->setOptions(['a' => 'a', 'b' => 'b']);

        $validator = new ValidateOption($field);
        $this->assertFalse($validator->validate('a'));
    }
}