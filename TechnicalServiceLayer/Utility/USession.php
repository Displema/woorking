<?php
namespace TechnicalServiceLayer\Utility;

/**
 * Credit to https://github.com/flebo45/Agora/blob/main/app/services/TechnicalServiceLayer/utility/USession.php
 */
class USession
{

    /**
     * Singleton class
     * Every edit on $_SESSION super global happens inside this file
     */

    private static ?USession $instance = null;

    private function __construct()
    {
        session_start();
       //start the session
    }

    public static function getInstance(): USession
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getSessionStatus(): int
    {
        return session_status();
    }


    public static function sessionUnset(): void
    {
        session_unset();
    }

    /**
     * unset of an element of _SESSION superglobal
     */
    public static function unsetElement($id): void
    {
        unset($_SESSION[$id]);
    }

    /**
     * destroy the session
     */
    public static function destroy(): void
    {
        session_destroy();
    }

    /**
     * get element in the _SESSION superglobal
     */
    public static function getSessionElement($id)
    {
        return $_SESSION[$id] ?? null;
    }

    /**
     * set an element in _SESSION superglobal
     */
    public static function setSessionElement($id, $value): USession
    {
        $_SESSION[$id] = $value;
        return self::getInstance();
    }

    /**
     * check if an element is set or not
     * @param mixed $id
     * @return boolean
     */
    public static function isSetSessionElement(mixed $id): bool
    {
        if (isset($_SESSION[$id])) {
            return true;
        }

        return false;
    }
}
