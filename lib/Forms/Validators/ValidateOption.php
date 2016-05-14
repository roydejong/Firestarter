<?php

namespace Firestarter\Forms\Validators;

use Firestarter\Forms\OptionField;
use Firestarter\Forms\Validator;

/**
 * A default validator for option fields: ensures that only valid values were selected.
 */
class ValidateOption extends Validator
{
    /**
     * Reference to the field being validated.
     *
     * @var OptionField
     */
    protected $field;

    /**
     * Copy of all options associated with the field being validated.
     *
     * @var array
     */
    private $options;

    /**
     * ValidateRequired constructor.
     *
     * @param OptionField $field
     */
    public function __construct(OptionField $field)
    {
        $this->field = $field;
        $this->options = $this->field->getOptions();
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if (empty($value)) {
            return false;
        }

        if ($this->field->getAllowMultiple()) {
            return $this->validateMultiple($value);
        } else {
            return $this->validateSingle($value);
        }
    }

    /**
     * Validates a value array, evaluating each option.
     *
     * @param array $value
     * @return bool
     */
    private function validateMultiple($value)
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $valueItem) {
            if (!$this->validateSingle($valueItem)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates whether a single string value is valid for the current field.
     *
     * @param string $value
     * @return bool
     */
    private function validateSingle($value)
    {
        foreach ($this->options as $optionValue => $label) {
            if ($optionValue === $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getError()
    {
        return 'Choose a valid option.';
    }
}