<?php
namespace Controller;

use DateTime;
use Delight\Auth\AttemptCancelledException;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UnknownIdException;
use Delight\Auth\UserAlreadyExistsException;
use Doctrine\ORM\Exception\ORMException;
use Exception;
use Model\ELocatore;
use Model\Enum\UserEnum;
use Model\EProfilo;
use TechnicalServiceLayer\Roles\Roles;
use View\VRedirect;
use View\VAuth;
use View\VResource;
use View\VStatus;

class CAuth extends BaseController
{
    public function showLoginForm(): void
    {
        if ($this->isLoggedIn()) {
            $redirectView = new VRedirect();
            $redirectView->redirect("/home");
            return;
        }
        
        $authView = new VAuth();
        $authView->showLoginForm();
    }

    public function showRegisterForm(): void
    {
        if ($this->auth_manager->isLoggedIn()) {
            $redirectView = new VRedirect();
            $redirectView->redirect("/home");
            return;
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
                ->setUserId($userId)
                ->setPhone($phone)
                ->setDob($date_parsed)
                ->setName($name)
                ->setSurname($surname);

            if ($userTypeParsed === UserEnum::Locatore) {
                if ($piva === null) {
                    die("PartitaIVA is null");
                }
                $profile->setPartitaIva($piva);
                $this->auth_manager->admin()->addRoleForUserById($userId, Roles::LANDLORD);
            } else {
                $this->auth_manager->admin()->addRoleForUserById($userId, Roles::BASIC_USER);
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
        } catch (UnknownIdException $e) {
            die('Unknown id');
        }

        $this->loginUser($email, $password, "0");

        $view = new VRedirect();
        $view->redirect("/home");
    }

    public function loginUser(string $email, string $password, string $rememberMe): void
    {
        if ($this->auth_manager->isLoggedIn()) {
            $redirectView = new VRedirect();
            $redirectView->redirect("/home");
            return;
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
            $userId = $this->auth_manager->getUserId();
            $repo = $this->entity_manager->getRepository(EProfilo::class);
            $profile = $repo->findOneBy(['user_id' => $userId]);

            //USession::setSessionElement("user", $profile);
            $view = new VRedirect();
            if ($this->doesLoggedUserHaveRole(Roles::ADMIN)) {
                $view->redirect("/admin/home");
            } else {
                $view->redirect("/home");
            }
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

    public function printUser(): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect("/login");
            return;
        }

        $user = $this->getUser();

        $view = new VResource();
        $view->printJson($user);
    }

    public function loginByEmail(string $email): void
    {
        if ($this->isLoggedIn()) {
            $this->logoutUser();
        }
        try {
            $this->auth_manager->admin()->logInAsUserByEmail($email);
        } catch (InvalidEmailException $e) {
            die('Invalid email address');
        }

        $user_id = $this->auth_manager->getUserId();
        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
            $targetClass = ELocatore::class;
        } else {
            $targetClass = EProfilo::class;
        }
        $user = $this->entity_manager->getRepository($targetClass)->findOneBy(['user_id' => $user_id]);
        //USession::setSessionElement("user", $user);
        $view = new VRedirect();
        $view->redirect("/home");
    }


    /**
     * @throws AuthError
     */
    public function logoutUser(): void
    {
        $this->auth_manager->logout();
        $view = new VRedirect();
        $view->redirect("/home");
    }

    public function modifyUser(
        string $nome,
        string $cognome,
        string $data_nascita,
        string $partiva_iva,
        string $telefono,
    ): void {
        $this->requireRole(Roles::LANDLORD);

        $user = $this->getUser();

        $user->setName($nome);
        $user->setSurname($cognome);
        try {
            $dob = new \DateTime($data_nascita);
            $user->setDob($dob);
        } catch (\Exception) {
            // Gestione errore: data non valida
            $view = new VStatus();
            $view->showStatus(400);
            return;
        }
        $user->setPhone($telefono);
        $user->setPartitaIva($partiva_iva);
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();

        //USession::setSessionElement("user", $user);

        $view = new VRedirect();
        $view->redirect("/home");
    }

    public function registerAdmin(): void
    {
        $userId = $this->auth_manager->register(
            "admin@admin.it",
            $_ENV['ADMIN_PASSWORD'] ?? 'admin',
            "admin"
        );
        $user = new EProfilo();
        $user
            ->setDob(new Datetime())
            ->setCreatedAt(new DateTime())
            ->setSurname("Admin")
            ->setName("Cooworking")
            ->setPhone("0123456789")
            ->setUserId($userId);
        $this->auth_manager->admin()->addRoleForUserById($userId, Roles::ADMIN);
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();
        $this->loginUser("admin@admin.it", $_ENV['ADMIN_PASSWORD'] ?? 'admin', 1);

        $view = new VRedirect();
        $view->redirect("/admin/home");
    }
    public function ResetPassword(): void
    {
        $view = new VAuth();
        $view->redirectResetPassword();
    }
}
