<?php

namespace Firestarter;

use Enlighten\Enlighten;

/**
 * Firestarter framework application instance.
 */
class Firestarter extends Enlighten
{
    /**
     * @var bool
     */
    protected $debugMode;

    /**
     * Initializes a new Firestarter application instance.
     */
    public function __construct()
    {
        parent::__construct();

        if (!defined('CWD')) {
            define('CWD', getcwd());
        }

        $this->setDebugMode(false);
    }

    /**
     * Enables or disables debug mode.
     *
     * @param bool $enabled
     */
    public function setDebugMode($enabled = true)
    {
        $this->debugMode = $enabled;
    }
}