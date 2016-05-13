<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\Validator;

/**
 * Validates a field value to be a valid integer.
 */
class ValidateInteger extends Validator
{
    /**
     * Allow zero values?
     *
     * @default false
     * @var bool
     */
    protected $allowZero;

    /**
     * Allow negative values?
     *
     * @default false
     * @var bool
     */
    protected $allowNegative;

    /**
     * ValidateInteger constructor.
     *
     * @param bool $allowZero Allow zero values?
     * @param bool $allowNegative Allow negative values?
     */
    public function __construct($allowZero = false, $allowNegative = false)
    {
        $this->allowZero = $allowZero;
        $this->allowNegative = $allowNegative;
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if (trim(strval($value)) == '')
        {
            // Empty field. Leave this to the "required" validator. Not our responsibility.
            return true;
        }

        $parsed = intval($value);

        if (strval($parsed) !== strval($value))
        {
            // The string value does not match the parsed value. A transformation occured here (e.g. stripped decimals)
            // or a string being cast to zero. Either way, a valid number was not entered.
            return false;
        }

        if (!$this->allowZero && $parsed == 0)
        {
            return false;
        }

        if (!$this->allowNegative && $parsed < 0)
        {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return "Enter a valid number.";
    }
}