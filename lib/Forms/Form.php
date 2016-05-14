<?php

namespace Firestarter\Forms;

use Enlighten\Http\Request;
use Firestarter\Views\View;

/**
 * Form generation and validation.
 */
class Form
{
    /**
     * Fields on the form.
     *
     * @var Field[]
     */
    protected $fields;

    /**
     * Submission method.
     *
     * @var string
     */
    protected $method;

    /**
     * Constructs a new form.
     *
     * @param string $method (FormMethod::POST, FormMethod::GET)
     */
    public function __construct($method = FormMethod::POST)
    {
        $this->clearFields();

        $this->method = $method;
    }

    /**
     * Validates the form based on an incoming request.
     *
     * @param Request $request
     * @return Field[] Returns an array of fields that failed validation.
     */
    public function validate(Request $request)
    {
        $failed = [];

        foreach ($this->fields as $field) {
            // Attempt to extract the field's value based on the form's request method (POST or GET)
            $value = null;

            if ($this->method == FormMethod::POST) {
                $value = $request->getPost($field->getName(), null);
            } else if ($this->method == FormMethod::GET) {
                $value = $request->getQueryParam($field->getName(), null);
            }

            // Set the field's value based on the submitted data, and validate it
            $field->setValue($value);

            if (!$field->validate()) {
                $failed[] = $field;
            }
        }

        return $failed;
    }

    /**
     * Returns the form submission method.
     *
     * @see FormMethod
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets all fields on this form.
     *
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Removes all fields from this form.
     */
    public function clearFields()
    {
        $this->fields = [];
    }

    /**
     * Retrieves a field by its name.
     *
     * @param string $name
     * @return Field|null
     */
    public function getField($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        return null;
    }

    /**
     * Sets multiple field values based on an array containing values indexed by field name.
     * Ignores invalid field names silently.
     *
     * @param array $data An array containing field values indexed by field name
     * @return int The amount of 
     */
    public function setValues(array $data)
    {
        $updated = 0;

        foreach ($data as $fieldName => $fieldValue) {
            $field = $this->getField($fieldName);

            if ($field) {
                $field->setValue($fieldValue);
                $updated++;
            }
        }

        return $updated;
    }

    /**
     * Sets a single field's value by field name.
     *
     * @param string $name Field name
     * @param string $value Field value
     * @return Field
     */
    public function setValue($name, $value)
    {
        $field = $this->getField($name);

        if ($field == null) {
            throw new \InvalidArgumentException("Invalid form field name: {$name}");
        }

        return $field->setValue($value);
    }

    /**
     * Retrieves a field's value by field name.
     *
     * @param string $name Field name
     * @return string Field value
     */
    public function getValue($name)
    {
        $field = $this->getField($name);

        if ($field == null) {
            throw new \InvalidArgumentException("Invalid form field name: {$name}");
        }

        return $field->getValue();
    }

    /**
     * Adds a field to the form.
     *
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[$field->getName()] = $field;
    }

    /**
     * Initializes and adds a field with a given name, and then returns it.
     * This utility function allows for method chaining without the verbosity of constructing a new field.
     *
     * @see addField
     * @param string $name Field name / ID. Must be unique on the form.
     * @param string $type Field type, e.g. "text", "email", "number", "checkbox", ...
     * @return Field
     */
    public function createField($name, $type = FieldType::TEXT)
    {
        $field = new Field($name, $type);
        $this->addField($field);
        return $field;
    }

    /**
     * Initializes and adds a field with a given name, and then returns it.
     * This utility function allows for method chaining without the verbosity of constructing a new field.
     *
     * @param string $name
     * @param string $type
     * @return OptionField
     */
    public function createOptionField($name, $type = FieldType::SELECT)
    {
        $field = new OptionField($name, $type);
        $this->addField($field);
        return $field;
    }

    /**
     * Renders the form.
     *
     * @return string
     */
    public function render()
    {
        $view = new View('forms/form.twig');
        $view->method = $this->method;
        $view->fields = $this->fields;
        return $view->render();
    }

    /**
     * @return RenderTool
     */
    public function getRenderTool()
    {
        return new RenderTool($this);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}