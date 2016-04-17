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
        $loader = new \Twig_Loader_Filesystem([
            CWD . '/views'
        ]);

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
            return true;
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
}