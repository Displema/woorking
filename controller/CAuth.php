<?php
namespace Controller;

use DateTime;
use Model\Enum\UserEnum;
use Model\EProfilo;

require_once __DIR__ . "/../bootstrap.php";

class CAuth
{
    public \Delight\Auth\Auth $auth_manager;
    public function getAuthManager(): \Delight\Auth\Auth
    {
        return $this->auth_manager;
    }

    public function setAuthManager(\Delight\Auth\Auth $auth_manager): CLogin
    {
        $this->auth_manager = $auth_manager;
        return $this;
    }

    public function __construct()
    {
    }

    public function registerUser()
    {
        try {
            $auth_manager = getAuth();
            $_POST["username"] = $_POST["email"];
            $selectors = "";
            $token = "";
            $userId = $auth_manager->register($_POST['email'], $_POST['password'], $_POST['username'], function ($selector, $token) {
                global $auth_manager;
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            echo 'We have signed up a new user with the ID ' . $userId;
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function loginUser() {
        try {
            $auth_manager = getAuth();

            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }
}
