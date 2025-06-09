<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use DateTime;
use Model\EFoto;
use Model\EPrenotazione;

use Model\EProfilo;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VReservation;
use View\VReview;
use View\VStatus;

class CReservation extends BaseController
{
    public function showreservation()
    {

        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $today = new DateTime();
        $repository = $this->entity_manager->getRepository(EPrenotazione::class);

        $user = USession::requireUser();
        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $reservations = $repository->findBy(['utente' => $user->getId()]);
        $reservationWithOffice = [];
        $oldreservationWithOffice = [];

        foreach ($reservations as $reservation) {
            $idOffice = $reservation->getUfficio();
            $photo = $this->entity_manager->getRepository(EFoto::class)->findOneBy(['ufficio' => $idOffice]);
            $office = $this->entity_manager->getRepository(EUfficio::class)->find($idOffice);

            if ($reservation->getData() >= $today) {
                if ($photo) {
                    $idPhoto = $photo->getId();

                    $photoUrl = "/static/img/" . $idPhoto;
                }
                $reservationWithOffice[] = [
                    'reservation' => $reservation,
                    'office' => $office,
                    'photo' => $photoUrl
                ];
            } else {
                if ($photo) {
                    $idPhoto = $photo->getId();

                    $photoUrl = "/static/img/" . $idPhoto;
                }
                $oldreservationWithOffice[] = [
                    'reservation' => $reservation,
                    'office' => $office,
                    'photo' => $photoUrl
                ];
            }
        }

        $view = new VReservation();
        $view->showReservation($reservationWithOffice, $oldreservationWithOffice, $user);
    }

    public function showReservationDetails($id)
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $reservationwithoffice = [];
        $repository = $this->entity_manager->getRepository(EPrenotazione::class);
        $reservation = $repository->find($id);
        $photoUrl = [];
        $office= $this->entity_manager->getRepository(EUfficio::class)->find($reservation->getUfficio());
        $photoOffice = $this->entity_manager->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $office->getId()]);
        foreach ($photoOffice as $photo) {
            if ($photo) {
                $idPhoto = $photo->getId();
                $photoUrl[] = "/static/img/" . $idPhoto;
            }
        }
        $reservationwithoffice[] = [
            'office' => $office,
            'photo' => $photoUrl,
            'reservation'=>$reservation
        ];

        $view = new VReservation();
        $view ->showReservationDetails($reservationwithoffice, $user);
    }

    public function sendreview($idreservation): void
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $user = USession::requireUser();

        $view = new VReview();
        $view ->showReviewForm($idreservation, $user);
    }

    public function confirmreview($idreservation)
        // TODO: rimuovere accesso ai dati diretto dall'array POST
    {
        if (!$this->auth_manager->isLoggedIn()) {
            $view = new VRedirect();
            $view->redirect('/login');
            return;
        }

        $userId = $this->auth_manager->getUserId();

        if (!($this->auth_manager->admin()->doesUserHaveRole($userId, Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        $value = $_POST['voto'];           // value 1-5
        $comment = $_POST['review']; // comment of review
        $user = USession::requireUser();
        $reservation = $this->entity_manager->getRepository(EPrenotazione::class)->find($idreservation);

        if (!$reservation) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $review= new ERecensione();
        $review->setCommento($comment);
        $review->setValutazione((int)$value);
        $review->setPrenotazione($reservation);

        $this->entity_manager->persist($review);
        $this->entity_manager->flush();

        $view = new VReview();
        $view->showReviewConfirmation($user);
    }
}
