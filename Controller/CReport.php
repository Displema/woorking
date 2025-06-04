<?php
namespace Controller;
use DateTime;
use Doctrine\ORM\EntityManager;
use Model\EProfilo;
use Model\ESegnalazione;
use Model\EUfficio;
use PHP_CodeSniffer\Reports\Report;

use TechnicalServiceLayer\Exceptions\UserNotAuthenticatedException;
use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use TechnicalServiceLayer\Utility\USession;
use View\VRedirect;
use View\VReport;


class CReport {

    public function showFormReport($id){
        $view = new VReport();
        $em =getEntityManager();
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $em->getRepository(EProfilo::class)->find($utente->getId());
        }
        $view->showReportForm($id,$user,$login);
    }
    public static function showConfirmOfReport($id) {
        $em =getEntityManager();
        $commento = $_POST['motivo'] ?? null;

        if($commento == 'Altro') {
            $commento = $_POST['altroTesto'] ?? null;
        }
        if (USession::isSetSessionElement('user')) {
            $utente = USession::requireUser();
            $login="isLoggedIn";
            $user = $em->getRepository(EProfilo::class)->find($utente->getId());
        }

     $ufficio=$em->getRepository(EUfficio::class)->find($id);

     $Report= new ESegnalazione();
     $Report->setCommento($commento);
     $Report->setUfficio($ufficio);
     $em->persist($Report);
     $em->flush();
     //$em->getRepository(ESegnalazione::class)->SaveReport($Report);

     $view = new VReport();
     $view->showReportConfirmation($user,$login);
    }
}