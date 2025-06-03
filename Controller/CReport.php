<?php
namespace Controller;
use DateTime;
use Doctrine\ORM\EntityManager;
use Model\ESegnalazione;
use Model\EUfficio;
use PHP_CodeSniffer\Reports\Report;

use TechnicalServiceLayer\Repository\EPrenotazioneRepository;
use View\VReport;


class CReport {

    public function showFormReport($id){
        $view = new VReport();
        $view->showReportForm($id);
    }
    public static function showConfirmOfReport($id) {

        $commento = $_POST['motivo'] ?? null;

        if($commento == 'Altro') {
            $commento = $_POST['altroTesto'] ?? null;
        }

     $em =getEntityManager();
     $ufficio=$em->getRepository(EUfficio::class)->find($id);

     $Report= new ESegnalazione();
     $Report->setCommento($commento);
     $Report->setUfficio($ufficio);
     $em->persist($Report);
     $em->flush();
     //$em->getRepository(ESegnalazione::class)->SaveReport($Report);

     $view = new VReport();
     $view->showReportConfirmation();
    }
}