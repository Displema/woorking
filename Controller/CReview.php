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
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VReservation;
use View\VReview;

class CReview extends BaseController
{
    public function getReviews()
    {

        /** @var ERecensioneRepository $repo */
        $repo = $this->entity_manager->getRepository(ERecensione::class);
        $user = USession::getUser();
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
}
