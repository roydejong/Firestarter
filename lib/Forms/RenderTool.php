<?php

namespace Firestarter\Forms;

use Enlighten\Http\Request;
use Firestarter\Views\View;

/**
 * Utility for procedurally rendering form fields. Useful when you need more fine-grained control over forms are rendered.
 */
class RenderTool
{
    /**
     * @var Form
     */
    private $form;

    /**
     * @var Field[]
     */
    private $fieldsUnrendered;

    /**
     * RenderTool constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
        $this->fieldsUnrendered = $form->getFields();
    }

    /**
     * Marks a field as rendered.
     *
     * @param Field $field
     */
    private function markFieldRendered(Field $field)
    {
        $key = array_search($field, $this->fieldsUnrendered);

        if ($key !== false) {
            unset($this->fieldsUnrendered[$key]);
        }
    }

    /**
     * Renders full field HTML.
     *
     * @param array $names Field names
     * @return string
     */
    public function renderFields(array $names)
    {
        foreach ($names as $name) {
            $this->renderField($name);
        }
    }

    /**
     * Renders full field HTML.
     *
     * @param $name Field name
     * @return string
     */
    public function renderField($name)
    {
        $field = $this->form->getField($name);
        return $this->renderFieldObj($field, false);
    }

    /**
     * Renders field HTML <input /> without labels or a wrapping group.
     *
     * @param $name Field name
     * @return string
     */
    public function renderInput($name)
    {
        $field = $this->form->getField($name);
        return $this->renderFieldObj($field, true);
    }

    /**
     * Renders a field.
     *
     * @param Field $field
     * @param bool $inputOnly
     * @return string
     */
    public function renderFieldObj(Field $field, $inputOnly = false)
    {
        $this->markFieldRendered($field);
        return $field->render($inputOnly);
    }

    /**
     * Renders all remaining fields.
     *
     * @return string
     */
    public function renderRemainder()
    {
        $renderOutput = '';

        foreach ($this->fieldsUnrendered as $field) {
            $renderOutput .= $this->renderFieldObj($field);
        }

        return $renderOutput;
    }
}