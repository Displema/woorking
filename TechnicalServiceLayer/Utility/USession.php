<?php
namespace TechnicalServiceLayer\Utility;

use Model\EProfilo;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticated;

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
        session_start(); //start the session
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
    public static function elementUnset($id)
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
        return $_SESSION[$id];
    }

    /**
     * @throws UserNotAuthenticated if the session doesn't contain a valid user
     */
    public static function requireUser(): EProfilo
    {
        if (!isset($_SESSION['user'])) {
            throw new UserNotAuthenticated();
        }

        return self::getSessionElement($_SESSION['user']);
    }

    /**
     * @throws UserNotAuthenticated
     */
    public static function requireAdmin(): EProfilo
    {
        if (self::isSetSessionElement('user')) {
            throw new UserNotAuthenticated();
        }

        $user = self::getSessionElement('user');
        if (!$user->isAdmin()) {
            throw new UserNotAuthenticated();
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
     * @return boolean
     */
    public static function isSetSessionElement($id)
    {
        if (isset($_SESSION[$id])) {
            return true;
        }

        return false;
    }
}
