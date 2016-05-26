<?php

namespace Firestarter\Views;

/**
 * A twig-based view renderer. Singleton.
 */
class ViewRenderer
{
    /**
     * @var ViewRenderer
     */
    private static $instance;

    /**
     * An array containing all global paths from which views should be loaded.
     *
     * @var string[]
     */
    private static $paths;

    /**
     * Registers a global path from which views can be loaded.
     *
     * @param string $path
     * @param int $priority The path's priority (lowest is attempted first).
     */
    public static function registerPath($path, $priority = 100)
    {
        // Determine a free "priority" key in our array (used for ksort later)
        while (isset(self::$paths[$priority])) {
            $priority++;
        }

        self::$paths[$priority] = $path;
    }

    /**
     * @return ViewRenderer
     */
    public static function instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new ViewRenderer();
        }

        return self::$instance;
    }

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Initializes a new view renderer.
     */
    public function __construct()
    {
        ksort(self::$paths);

        $loader = new \Twig_Loader_Filesystem(self::$paths);

        $this->twig = new \Twig_Environment($loader, [
            'cache' => $this->initializeCache(),
            'debug' => true
        ]);
    }

    /**
     * Initializes the Twig cache directory.
     *
     * @return string|bool Returns the Twig template cache directory, or FALSE if cache could not be initialized.
     */
    private function initializeCache()
    {
        $cacheDir = CWD . '/cache';
        $twigCacheDir = CWD . '/cache/twig';

        if (!file_exists($cacheDir)) {
            @mkdir($cacheDir);
        }

        if (!file_exists($twigCacheDir)) {
            @mkdir($twigCacheDir);
        }

        if (file_exists($twigCacheDir) && is_writable($twigCacheDir)) {
            return $twigCacheDir;
        }

        return false;
    }

    /**
     * Renders a template with a set of contextual data as a string.
     *
     * @param string $name
     * @param array $data
     * @return string
     */
    public function render($name, array $data)
    {
        return $this->twig->render($name, $data);
    }

    /**
     * Registers a custom Twig filter.
     * 
     * @param \Twig_SimpleFilter $filter
     */
    public function addFilter(\Twig_SimpleFilter $filter)
    {
        $this->twig->addFilter($filter);
    }
}