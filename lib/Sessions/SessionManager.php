<?php

namespace Firestarter\Sessions;

use Enlighten\Http\Request;

/**
 * Abstract base for a session manager.
 */
abstract class SessionManager
{
    /**
     * The name of the session managed by this session manager.
     * This is used to name the user cookie appropriately.
     *
     * @var string
     */
    private $sessionName;

    /**
     * The incoming HTTP request.
     *
     * @var Request
     */
    private $request;

    /**
     * Initializes a new session manager with a given name.
     *
     * @param Request $request The HTTP request we are initializing a session manager for.
     * @param string $sessionName
     */
    public function __construct(Request $request, $sessionName)
    {
        $this->request = $request;
        $this->sessionName = $sessionName;
    }

    /**
     * Starts the session. Must be called before reading/setting data.
     */
    public function start()
    {
        session_name($this->sessionName);
        session_set_cookie_params(0, '/', $this->getCookieDomain(), $this->getCookieIsSecure(), true);
        session_start();
    }

    /**
     * Returns the domain name for the session cookie.
     *
     * @return string
     */
    private function getCookieDomain()
    {
        return $this->request->getHostname();
    }

    /**
     * Returns whether the session cookie should be marked as secure (HTTPS only) or not.
     *
     * @return bool
     */
    private function getCookieIsSecure()
    {
        return $this->request->isHttps();
    }

    /**
     * Gets a session value.
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return string|mixed The value or, if not found, $defaultValue.
     */
    public function get($key, $defaultValue = null)
    {
        if (!isset($_SESSION[$key]))
        {
            return $defaultValue;
        }

        return $_SESSION[$key];
    }

    /**
     * Sets a session value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unsets a session value
     *
     * @param string $key
     */
    public function unset($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroys the user session, clearing it of all data and completely resetting its state.
     * Results in a new session ID.
     */
    public function destroy()
    {
        session_destroy();
        $_SESSION = [];
        $this->start();
    }
}