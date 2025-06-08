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


class CReview
{

    private EntityManager $entity_manager;

    public function __construct()
    {
        $this->entity_manager = getEntityManager();
    }

    public function getReviews()
    {

        /** @var ERecensioneRepository $repo */
        $repo = getEntityManager()->getRepository(ERecensione::class);
        $user = USession::requireUser();
        $userId = $user->getId();
        /** @var EUfficioRepository $uffici */
        $uffici = getEntityManager()->getRepository(EUfficio::class)->getOfficeByLocatore($userId);
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