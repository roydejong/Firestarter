<?php

namespace Firestarter\Forms;

use Firestarter\Forms\Validators\ValidateMaxLength;
use Firestarter\Forms\Validators\ValidateMinLength;
use Firestarter\Forms\Validators\ValidateRequired;
use Firestarter\Views\View;

/**
 * A general purpose single option text input field.
 *
 * @see Form
 */
class Field
{
    /**
     * The name of this form field. Used to identify it in the HTML, and used to retrieve its value.
     *
     * @var string
     */
    protected $name;

    /**
     * The type of field (e.g. "text", "hidden", "radio", "select", ...)
     *
     * @see FieldType
     * @var string
     */
    protected $type;

    /**
     * The field's value. Either the default value, or the submitted value.
     *
     * @default null
     * @var string
     */
    protected $value;

    /**
     * Validation chain for this field.
     *
     * @var Validator[]
     */
    protected $validators;

    /**
     * Error message as a result of validation failures.
     *
     * @default <empty string>
     * @var string
     */
    protected $error;

    /**
     * Label for this field, used when rendering it.
     *
     * @var string
     */
    protected $label;

    /**
     * Key/value array containing a set of custom HTML attributes used when rendering a field.
     *
     * @var array
     */
    protected $properties;

    /**
     * Field constructor.
     *
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type = FieldType::TEXT)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = null;
        $this->validators = [];
        $this->error = '';
        $this->properties = [];
    }

    /**
     * Gets the field's name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the field's type.
     *
     * @see FieldType
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Clean, normalize and set the field's default or submitted value to the one provided.
     * This value is then used for any future validations or field rendering.
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = trim(strval($value));
        return $this;
    }

    /**
     * Gets the field's current or default value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Adds a validator to this field.
     *
     * @param Validator $validator
     * @param int $priority The validator priority in the chain (lower is executed first).
     * @return $this
     */
    public function addValidator(Validator $validator, $priority = 100)
    {
        // Determine a free "priority" key in our array (used for ksort later)
        while (isset($this->validators[$priority])) {
            $priority++;
        }

        $this->validators[$priority] = $validator;
        return $this;
    }

    /**
     * Removes all validators with a given $className.
     *
     * @param string $className The class name, optionally with full namespace.
     * @return $this
     */
    public function removeValidatorsByName($className)
    {
        $toUnset = [];

        foreach ($this->validators as $key => $validator) {
            $validatorClassNameFull = get_class($validator);
            $validatorClassNameNsParts = explode('\\', $validatorClassNameFull);
            $validatorClassName = array_pop($validatorClassNameNsParts);

            if ($className == $validatorClassName || $className == $validatorClassNameFull) {
                $toUnset[] = $key;
            }
        }

        foreach ($toUnset as $keyToUnset) {
            unset($this->validators[$keyToUnset]);
        }

        return $this;
    }

    /**
     * Controls whether this field is required to be filled in or not.
     * Adds a ValidateRequired validator.
     *
     * @param bool $required
     * @return $this
     */
    public function setRequired($required = true)
    {
        $this->removeValidatorsByName('ValidateRequired');

        if ($required) {
            $this->addValidator(new ValidateRequired());
            $this->setProperty('required', 'required');
        }

        return $this;
    }

    /**
     * Configure a minimum length for this field's value.
     *
     * @param int $length
     * @return $this
     */
    public function setMinLength($length)
    {
        $this->removeValidatorsByName('ValidateMinLength');
        $this->unsetProperty('minlength');

        if ($length > 0) {
            $this->addValidator(new ValidateMinLength($length));
        } else if ($length == 0) {
            // Min length is zero, which in reality just means: this field is required
            $this->setRequired(true);
            $this->setProperty('minlength', $length);
        }

        return $this;
    }

    /**
     * Configure a maximum length for this field's value.
     *
     * @param int $length
     * @return $this
     */
    public function setMaxLength($length)
    {
        $this->removeValidatorsByName('ValidateMaxLength');
        $this->unsetProperty('maxlength');

        if ($length > 0) {
            $this->addValidator(new ValidateMaxLength($length));
            $this->setProperty('maxlength', $length);
        }

        return $this;
    }

    /**
     * Validates the current field value.
     *
     * @return bool TRUE of successfully validated, or FALSE if validation failed.
     */
    public function validate()
    {
        // Sort validators by their priority (as stored in the array keys).
        ksort($this->validators);

        foreach ($this->validators as $validator) {
            if (!$validator->validate($this->getValue())) {
                // Validation has failed for our current value
                // Set error message and return - the chain has broken
                $this->setError($validator->getError());
                return false;
            }
        }

        // All validators have passed (or no validators set)
        return true;
    }

    /**
     * Sets a HTML attribute, used for rendering only.
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
        return $this;
    }

    /**
     * @param string $key The property key to look up.
     * @param mixed $default The default return value if the property is not set.
     * @return string
     */
    public function getProperty($key, $default = null)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function unsetProperty($key)
    {
        if (isset($this->properties[$key])) {
            unset($this->properties[$key]);
        }
        
        return $this;
    }

    /**
     * @param $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Renders this form field.
     *
     * @param bool $inputOnly If true, only render the actual <input /> element without a group and label surrounding it.
     * @return string
     */
    public function render($inputOnly = false)
    {
        $view = new View('forms/fields/field.twig');
        $view->inputOnly = $inputOnly;
        $view->name = $this->getName();
        $view->value = $this->getValue();
        $view->label = $this->getLabel();
        $view->error = $this->getError();
        $view->type = $this->getType();
        $view->properties = $this->properties;
        return $view->render();
    }
}