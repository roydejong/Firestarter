<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\Validator;

/**
 * Validates whether a field has a minimum character length.
 */
class ValidateMaxLength extends Validator
{
    /**
     * Max length.
     *
     * @var int
     */
    protected $length;

    /**
     * ValidateMaxLength constructor.
     *
     * @param int $length Max length.
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
        if (strlen($value) > $this->length) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return "Enter no more than {$this->length} characters.";
    }
}