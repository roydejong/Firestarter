<?php

namespace Firestarter\Forms;

/**
 * Enum of standard field types available.
 */
class FieldType
{
    /**
     * A simple, general-purpose text input.
     */
    const TEXT = 'text';

    /**
     * A simple, general-purpose text input that requires a valid e-mail address.
     */
    const EMAIL = 'email';

    /**
     * A simple, general-purpose text input that is not visible to the user.
     */
    const HIDDEN = 'hidden';

    /**
     * A large text area that supports multi-line input.
     */
    const TEXTAREA = 'textarea';

    /**
     * A multi-option input. Up to one option can be selected.
     */
    const RADIO = 'radio';

    /**
     * A multi-option dropdown menu. Up to one option can be selected.
     */
    const SELECT = 'select';

    /**
     * A boolean checkbox. Can be true (1) or false (null).
     */
    const CHECKBOX_BOOLEAN = 'checkbox_boolean';
    
    /**
     * A multi-option input that contains several checkboxes.
     * Each checkbox can be checked or unchecked individually.
     * The posted data will be an array.
     */
    const CHECKBOX_MULTI = 'checkbox_multi';
    
}