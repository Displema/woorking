<?php
namespace Controller;

use Couchbase\User;
use DateTime;
use Delight\Auth\AttemptCancelledException;
use Delight\Auth\AuthError;
use Doctrine\ORM\Exception\ORMException;
use Model\ELocatore;
use Model\Enum\UserEnum;
use Model\EProfilo;
use TechnicalServiceLayer\Utility\USession;

require_once __DIR__ . "/../bootstrap.php";

class CAuth
{
    public \Delight\Auth\Auth $auth_manager;
    private \Doctrine\ORM\EntityManager $entity_manager;

    public function getAuthManager(): \Delight\Auth\Auth
    {
        return $this->auth_manager;
    }

    public function setAuthManager(\Delight\Auth\Auth $auth_manager): CAuth
    {
        $this->auth_manager = $auth_manager;
        return $this;
    }

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
        $this->auth_manager = getAuth();
    }

    public function registerUser(
        string $email,
        string $password,
        string $name,
        string $surname,
        string $date,
        string $userType,
        string $phone,
        string $piva = null
    ): void {
        try {
            $date = new DateTime($date);
        } catch (\Exception $e) {
            die("Wrong date format");
        }

        $userTypeParsed = UserEnum::tryFrom($userType);

        if ($userTypeParsed === null) {
            die("Wrong user type");
        }


        try {
            // Using callback:null ensures that the email address is verified on the spot
            $userId = $this->auth_manager->register(
                email: $email,
                password: $password,
                username: $email,
                callback: null
            );



            $model = $userType === UserEnum::Utente ? EProfilo::class : ELocatore::class;
            $profile = new $model();
            $profile
                ->setIdUtente($userId)
                ->setTelefono($phone)
                ->setDataNascita($date)
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
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        } catch (AuthError $e) {
        } catch (ORMException $e) {
        }
    }

    public function loginUser(string $email, string $password, string $rememberMe = "0"): void
    {
        try {
            if ((bool) $rememberMe) {
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

            echo "User is logged in. Your profile is $profile";
        } catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        } catch (AttemptCancelledException|AuthError $e) {
        }
    }
}
