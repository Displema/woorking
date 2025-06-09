<?php
namespace TechnicalServiceLayer\Utility;

use Model\EProfilo;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;

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

    public static function getSessionStatus()
    {
        return session_status();
    }


    public static function sessionUnset()
    {
        session_unset();
    }

    /**
     * unset of an element of _SESSION superglobal
     */
    public static function unsetElement($id)
    {
        unset($_SESSION[$id]);
    }

    /**
     * destroy the session
     */
    public static function destroy()
    {
        session_destroy();
    }

    /**
     * get element in the _SESSION superglobal
     */
    public static function getSessionElement($id)
    {
        if (isset($_SESSION[$id])) {
            return $_SESSION[$id];
        }
        return null;
    }


    /**
     * @throws UserNotAuthenticatedException if the session doesn't contain a valid user
     */
    public static function getUser(): EProfilo
    {
        if (!self::isSetSessionElement('user')) {
            throw new UserNotAuthenticatedException();
        }

        return self::getSessionElement('user');
    }

    /**
     * @throws UserNotAuthenticatedException
     */
    public static function requireAdmin(): EProfilo
    {
        if (!self::isSetSessionElement('user')) {
            throw new UserNotAuthenticatedException();
        }

        $user = self::getSessionElement('user');
        if (!$user->isAdmin()) {
            throw new UserNotAuthenticatedException();
        }
        return $user;
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
