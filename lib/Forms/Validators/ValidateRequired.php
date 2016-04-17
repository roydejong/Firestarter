<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\Validator;

/**
 * Validates a required field for a non-empty value.
 */
class ValidateRequired extends Validator
{
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if ($value === null)
        {
            // This is a NULL value, which probably means no value was ever submitted. That's not okay.
            return false;
        }

        if ($value !== 0 && !is_array($value) && empty(trim(strval($value))))
        {
            // This value isn't zero, it isn't an array, but it is empty. That's not okay.
            return false;
        }

        // Looks like this field contains something.
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return 'Enter a value.';
    }
}