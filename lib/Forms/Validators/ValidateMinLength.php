<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\Validator;

/**
 * Validates whether a field has a minimum character length.
 */
class ValidateMinLength extends Validator
{
    /**
     * Min length.
     *
     * @var int
     */
    protected $length;

    /**
     * ValidateMinLength constructor.
     *
     * @param int $length Min length.
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if (empty($value)) {
            // This value is empty. Only the "required" validator can reject this.
            return true;
        }

        if (strlen($value) < $this->length) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return "Enter at least {$this->length} characters.";
    }
}