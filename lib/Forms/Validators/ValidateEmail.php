<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\Validator;

/**
 * Validates whether a field has a minimum character length.
 */
class ValidateEmail extends Validator
{
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if (empty($value)) {
            // This value is empty. Only the "required" validator can reject this.
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return "Enter a valid e-amil address.";
    }
}