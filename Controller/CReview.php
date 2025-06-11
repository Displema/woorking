<?php
namespace Controller;

use Doctrine\ORM\EntityManager;
use DateTime;
use Model\EFoto;
use Model\EPrenotazione;

use Model\EProfilo;
use Model\ERecensione;
use Model\EUfficio;
use TechnicalServiceLayer\Repository\ERecensioneRepository;
use TechnicalServiceLayer\Repository\EUfficioRepository;
use TechnicalServiceLayer\Roles\Roles;
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VReservation;
use View\VReview;
use View\VStatus;

class CReview extends BaseController
{
    public function getReviews()
    {

        /** @var ERecensioneRepository $repo */
        $repo = $this->entity_manager->getRepository(ERecensione::class);
        $user = $this->getUser();
        $userId = $user->getId();
        /** @var EUfficioRepository $officeRepo */
        $officeRepo = $this->entity_manager->getRepository(EUfficio::class);
        $uffici = $officeRepo->getAllOfficeByLocatore($userId);
        $arrayReviews = [];
        foreach ($uffici as $ufficio) {
            $reviews = $repo->getRecensioneByUfficio($ufficio->getId());
            foreach ($reviews as $review) {
                $arrayReviews[] = $review;
            }
        }

        $view = new VReview();
        $view->reviews($arrayReviews);
    }
    public function showReviews($id)
    {
        //check if the user is looged
        if ($this->isLoggedIn()) {
            //take the user from the session
            $user = $this->getUser();
        } else {
            $user = null;
        }

        //take the office from the DB with the repository
        $office = $this->entity_manager->getRepository(EUfficio::class)->find($id);

        //check if the office exist
        if (!$office) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        //take the reporitory of Erecensione to use the function that take the reviews from the office
        /** @var ERecensioneRepository $repo */
        $repo=$this->entity_manager->getRepository(ERecensione::class);
        $review = $repo->getRecensioneByUfficio($id);

        $view = new VReview();
        $view->showAllReviews($review, $office, $user);
    }
    public function reviewForm($idreservation): void
    {
    //chekc if the user is logged
        $this->requireLogin();

        //check the role of user
        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }
        //take the user from the session
        $user = $this->getUser();
        //show form for review
        $view = new VReview();
        $view ->showReviewForm($idreservation, $user);
    }
    public function storeReview($idreservation, $voto, $comment)
    {
        $this->requireLogin();

        $userId = $this->auth_manager->getUserId();

        if (!($this->doesLoggedUserHaveRole(Roles::BASIC_USER))) {
            $view = new VRedirect();
            $view->redirect('/home');
            return;
        }

        //$value = $_POST['voto'];           // value 1-5
        //$comment = $_POST['review']; // comment of review
        $user = $this->getUser();
        $reservation = $this->entity_manager->getRepository(EPrenotazione::class)->find($idreservation);

        if (!$reservation) {
            $view = new VStatus();
            $view->showStatus(404);
            return;
        }

        $review= new ERecensione();
        $review->setCommento($comment);
        $review->setValutazione((int)$voto);
        $review->setPrenotazione($reservation);

        $this->entity_manager->persist($review);
        $this->entity_manager->flush();

        $view = new VReview();
        $view->showReviewConfirmation($user);
    }
}
