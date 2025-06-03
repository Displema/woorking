<?php
namespace Controller;

use DateTime;
use Delight\Auth\AttemptCancelledException;
use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Model\ELocatore;
use Model\Enum\UserEnum;
use Model\EProfilo;
use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VAuth;
use View\VResource;

require_once __DIR__ . "/../bootstrap.php";

class CAuth
{
    public Auth $auth_manager;
    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth_manager = getAuth();
    }

    public function showLoginForm(): void
    {
        $currentUser = USession::isSetSessionElement("user");
        if ($currentUser) {
            $redirectView = new VRedirect();
            $redirectView->redirect("/home");
        }

        $authView = new VAuth();
        $authView->showLoginForm();
    }

    public function showRegisterForm(): void
    {
        $currentUser = USession::isSetSessionElement("user");
        if ($currentUser) {
            $redirectView = new VRedirect();
            $redirectView->redirect("/home");
        }

        $authView = new VAuth();
        $authView->showRegisterForm();
    }
    public function registerUser(
        string $name,
        string $surname,
        string $dob,
        string $phone,
        string $email,
        string $password,
        string $userType,
        string $piva = null

    ): void {
        try {
            $date_parsed = new DateTime($dob);
        } catch (Exception) {
            die("Wrong date format");
        }

        $userTypeParsed = UserEnum::tryFrom($userType);

        if ($userTypeParsed === null) {
            die("Wrong user type");
        }

        if (strlen($password) < 8) {
            die("Please enter a stronger password");
        }

        try {
            // Using callback:null ensures that the email address is verified on the spot
            $userId = $this->auth_manager->register(
                email: $email,
                password: $password,
                username: $email,
                callback: null
            );


            $model = $userTypeParsed === UserEnum::Utente ? EProfilo::class : ELocatore::class;
            $profile = new $model();
            $profile
                ->setIdUtente($userId)
                ->setTelefono($phone)
                ->setDataNascita($date_parsed)
                ->setNome($name)
                ->setCognome($surname);

            if ($userTypeParsed === UserEnum::Locatore) {
                if ($piva === null) {
                    die("PartitaIVA is null");
                }
                $profile->setPartitaIva($piva);
            }

            $this->entity_manager->persist($profile);
            $this->entity_manager->flush();
        } catch (InvalidEmailException $e) {
            die('Invalid email address');
        } catch (InvalidPasswordException $e) {
            die('Invalid password');
        } catch (UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (TooManyRequestsException $e) {
            die('Too many requests');
        } catch (ORMException $e) {
            die('ORM error');
        }

        $view = new VRedirect();
        $view->redirect("/home");
    }

    public function loginUser(string $email, string $password, string $rememberMe = "0"): void
    {
        $currentUser = USession::isSetSessionElement("user");
        if ($currentUser) {
            $view = new VRedirect();
            $view->redirect("/home");
        }

        try {
            if ($rememberMe) {
                // Remember the user for 30 days
                $duration = 60*60*24*30;
            } else {
                // Remember the user for 1 day
                $duration = 60*60*24;
            }
            $this->auth_manager->login($email, $password, $duration);
            $userid = $this->auth_manager->getUserId();
            $repo = $this->entity_manager->getRepository(EProfilo::class);
            $profile = $repo->findOneBy(['idUtente' => $userid]);

            USession::setSessionElement("user", $profile);

            $view = new VRedirect();
            $view->redirect("/home");
        } catch (InvalidEmailException $e) {
            die('Wrong email address');
        } catch (InvalidPasswordException $e) {
            die('Wrong password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (TooManyRequestsException $e) {
            die('Too many requests');
        } catch (AttemptCancelledException|AuthError $e) {
            die('An error occurred');
        }
    }

    public function getUser(): void
    {
        try {
            $user = USession::requireUser();
        } catch (UserNotAuthenticatedException $e) {
            $view = new VRedirect();
            $view->redirect("/error");
            return;
        }

        $view = new VResource();
        $view->printJson($user);
    }
}
