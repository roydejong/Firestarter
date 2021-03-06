<?php

namespace Firestarter;

use ActiveRecord\Config;
use ActiveRecord\Connection;
use ActiveRecord\DateTime;
use Enlighten\Enlighten;
use Firestarter\Views\ViewRenderer;

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

        // Environment config
        if (!defined('CWD')) {
            define('CWD', getcwd());
        }

        if (!defined('FIRE_DIR')) {
            define('FIRE_DIR', realpath(__DIR__ . '/../'));
        }

        $this->setDebugMode(false);

        // Initialize view loading paths
        if (file_exists(CWD . '/views')) {
            ViewRenderer::registerPath(CWD . '/views', 500);
        }

        ViewRenderer::registerPath(FIRE_DIR . '/views', 1000);
        
        $this->context->registerInstance(ViewRenderer::instance());
    }

    /**
     * Configures the database connection.
     *
     * @param string $connectionString Connection string (e.g. "mysql://username:password@localhost/development?charset=utf8")
     */
    public function setDatabaseConnection($connectionString)
    {
        DateTime::$DEFAULT_FORMAT = 'db';
        Connection::$datetime_format = DateTime::$FORMATS['db'];

        Config::initialize(function(Config $config) use ($connectionString) {
            $modelDirectory = CWD . '/models';

            if (!file_exists($modelDirectory)) {
                @mkdir($modelDirectory);
            }

            $config->set_model_directory($modelDirectory);
            $config->set_default_connection('primary');
            $config->set_connections(['primary' => $connectionString]);
        });
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

    /**
     * Gets whether debug mode is enabled or not.
     *
     * @return bool
     */
    public function getDebugMode()
    {
        return $this->debugMode;
    }
}