<?php
namespace App\Frameworks;

class StateManager
{
    /**
     * Initialize the state storage if it's not already initialized.
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retrieve a value from the state storage.
     */
    public static function retrieve($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Store a value in the state storage.
     */
    public static function store($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a key exists in the state storage.
     */
    public static function exists($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a key from the state storage.
     */
    public static function discard($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Clear all data from the state storage.
     */
    public static function clearAll()
    {
        $_SESSION = [];
    }

    /**
     * Terminate the state storage.
     */
    public static function terminate()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }
}
