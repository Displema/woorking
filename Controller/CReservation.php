<?php
namespace Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use DateTime;
use Model\EFoto;
use Model\Enum\FasciaOrariaEnum;
use Model\EPrenotazione;

use Model\EProfilo;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VOffice;
use View\VRedirect;
use View\VReservation;
use View\VReview;
use View\VStatus;

class CReservation extends BaseController
{
    public function index()
    {
        //check if the user is logged
        $this->requireLogin();
        //check the role of user
        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        //take the datetime of today
        $today = new DateTime();

        //take the repository of EPrenotazione
        $repository = $this->entity_manager->getRepository(EPrenotazione::class);

        //take the user from session
        $user = $this->getUser();

        //take the reservation by the user
        $reservations = $repository->findBy(['utente' => $user->getId()]);
        foreach ($reservations as $reservation) {
            error_log($reservation->getId());
        }

        $activereservation=[];
        $pastreservation=[];

        foreach ($reservations as $reservation) {
            //take the date from reservation to check what reservation are old
            // and what are active
            $Date = $reservation->getData();


            if ($Date >= $today) {
                $activereservation[] = [
                    'reservation' => $reservation,
                ];
            } else {
                $pastreservation[] = [
                    'reservation' => $reservation,
                ];
            }
        }
        $view = new VReservation();
        $view->showReservation($activereservation, $pastreservation, $user);
    }


    public function show($id)
    {
   //check if the user is logged
        if (!$this->auth_manager->isLoggedIn()) {
            //redirect to the login to take access
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }
        //take the user form the session
        $user = $this->getUser();
        //take the userid from the authmanager
        //check the role of the user
        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        //from the repository take the reservation to show information
        $repository = $this->entity_manager->getRepository(EPrenotazione::class);
        $reservation = $repository->find($id);

        $view = new VReservation();
        $view ->showReservationDetails($reservation, $user);
    }
    public function confirmReservation($idOffice, $slot, $date)
    {
        //check if the user is logged
        $this->requireLogin();

        //check the role of user
        if ($this->doesLoggedUserHaveRole(Roles::LANDLORD)) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        // take the user from the session
        $user = $this->getUser();

        // take from the DB the user usgin the repository of EProfilo and method of entitymanager
        // but with the id of the user of session
        $usertosave=$this->entity_manager->getRepository(EProfilo::class)->findOneBy(['id'=> $user->getId()]);

        //conversion of date from String to datetime
        $date_parsed = new DateTime($date);

        //conversion of slot from string to FasciaOrariaEnum
        $slotEnum=FasciaOrariaEnum::from($slot);

        //start of transaction to prepare the possibility save of new  reservation
        $this->entity_manager->beginTransaction();
        try {
            // With PessimisticWrite the first one that gets access to the office locks it until it's finished
            //$office = $this->entity_manager->getRepository(EUfficio::class)->find($idOffice, LockMode::PESSIMISTIC_WRITE);
            $office = $this->entity_manager->find(EUfficio::class, $idOffice, LockMode::PESSIMISTIC_WRITE);


            //check if office exist
            if (!$office) {
                $this->entity_manager->rollback();
                $view = new VStatus();
                $view->showStatus(404);
                return;
            }

            //take the number of reservation for this office in a specific date and slot
            $reservationCount = $this->entity_manager->getRepository(EPrenotazione::class)->getActiveReservationsByOfficeDateSlot($office, $date_parsed, $slotEnum);

            //take the number of place for office
            $placesAvaible = $office->getNumeroPostazioni();

            //check if the office not is full
            if ($reservationCount >= $placesAvaible) {
                //if the office is full , show the page placenotavaible
                $view  = new VReservation();
                $view ->showAlreadyBookedPage($user);
                exit; //to don't save the reservation
            }
            //else creation of reservation and setting of parameters
            $reservation = new EPrenotazione();
            $reservation->setData(new DateTime($date));
            $reservation->setUfficio($office);
            $reservation->setFascia($slotEnum);
            $reservation->setUtente($usertosave);

            //method of entitymanager to prepare and save the reseration
            $this->entity_manager->persist($reservation); //prepare the reservation to be save
            $this->entity_manager->flush(); //set the information of reservation

            // commit don't is used in directly way but to user PESSIMISTIC WRITE is very important
            // because allow to book an office only after a commit
            $this->entity_manager->commit();

            $view = new VOffice();
            $view->showconfirmedpage1($user);
        } catch (Exception $e) {
            // if the saving have problem rollback cancel everything
            $this->entity_manager->rollback();
            echo $e->getMessage();

        }
    }
}
