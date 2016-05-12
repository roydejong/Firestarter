<?php

namespace Firestarter\Forms;
use Firestarter\Views\View;

/**
 * A form field that presents a collection of options.
 */
class OptionField extends Field
{
    /**
     * Value => label array containg all form options.
     *
     * @var array
     */
    protected $options;

    /**
     * Flag indicating wheter this is a multi-option field or not.
     *
     * @var bool
     */
    protected $multiOption;

    /**
     * @inheritdoc
     */
    public function __construct($name, $type)
    {
        parent::__construct($name, $type);

        $this->options = [];
        $this->multiOption = false;

        switch ($type) {
//            case FieldType::SELECT:
//                $this->viewName = 'forms/fields/select.twig';
//                $this->multiOption = false;
//                break;
//            case FieldType::CHECKBOX_BOOLEAN:
//                $this->viewName = 'forms/fields/checkbox.twig';
//                $this->multiOption = false;
//                break;
//            case FieldType::CHECKBOX_MULTI;
//                $this->viewName = 'forms/fields/checkbox_multi.twig';
//                $this->multiOption = false;
//                break;
            case FieldType::RADIO:
                $this->viewName = 'forms/fields/radio.twig';
                $this->multiOption = true;
                break;
        }
    }

    /**
     * Adds an option.
     *
     * @param string $value
     * @param string $label
     * @return $this
     */
    public function addOption($value, $label)
    {
        $this->options[$value] = $label;
        return $this;
    }

    /**
     * Sets an array of options.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Gets all options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Given an option $value, returns the associated label.
     *
     * @param string $value
     * @return mixed|null
     */
    public function getOptionLabel($value)
    {
        return isset($this->options[$value]) ? $this->options[$value] : null;
    }

    /**
     * Removes all options.
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = [];
        return $this;
    }

    /**
     * Sets whether this form field should accept multiple values as input or not.
     * If true, the value of this field will be an array.
     *
     * @param bool $multi
     * @return $this
     */
    public function setAllowMultipile($multi = true)
    {
        $this->multiOption = $multi;
        $this->checkMultiValue();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        parent::setValue($value);
        $this->checkMultiValue();
        return $this;
    }

    /**
     * Strictly enforces the field's value type based on multi select enabled state.
     */
    private function checkMultiValue()
    {
        if ($this->multiOption && !is_array($this->value)) {
            $this->value = [strval($this->value)];
        } else if (!$this->multiOption && is_array($this->value)) {
            $this->value = strval(array_shift($this->value));
        }
    }

    /**
     * Gets whether this form field should accept multiple values as input or not.
     * If true, the value of this field will be an array.
     *
     * @return bool
     */
    public function getAllowMultiple()
    {
        return $this->multiOption;
    }

    /**
     * @inheritdoc
     */
    protected function setViewData(View $view)
    {
        parent::setViewData($view);

        $view->options = $this->options;
        $view->multi = $this->multiOption;
    }
}