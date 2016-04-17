<?php

namespace Firestarter\Forms;

use Enlighten\Http\Request;

/**
 * A form field validator.
 */
abstract class Validator
{
    /**
     * Validates a value.
     *
     * @param mixed $value The submitted value, to be validated.
     * @return boolean TRUE if validation PASSES, or FALSE if validation FAILED.
     */
    public abstract function validate($value);

    /**
     * Returns the validation error message.
     *
     * @return string
     */
    public abstract function getError();
}