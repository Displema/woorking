<?php
namespace Controller;

use DateTime;
use Model\EFoto;
use Model\EPrenotazione;

use Model\ERecensione;
use Model\EUfficio;
use View\VPrenotazioni;
use View\VRecensioni;


class CReservation
{

    public function showreservation()
    {
        $today = new DateTime();
        $em = getEntityManager();
        $repository = $em->getRepository(EPrenotazione::class);
        $reservation = $repository->findAll();
        $reservationWithOffice = [];
        $oldreservationWithOffice = [];

        foreach ($reservation as $reservation) {
            $idOffice = $reservation->getUfficio();
            $photo = $em->getRepository(EFoto::class)->findOneBy(['ufficio' => $idOffice]);
            $office = $em->getRepository(EUfficio::class)->find($idOffice);

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

        $view = new VPrenotazioni();
        $view->showPrenotation($reservationWithOffice, $oldreservationWithOffice);

    }

    public function showReservationDetails($id)
    {
        $reservationwithoffice = [];

        $em = getEntityManager();
        $repository = $em->getRepository(EPrenotazione::class);
        $reservation = $repository->find($id);
        $photoUrl = [];
        $office= $em->getRepository(EUfficio::class)->find($reservation->getUfficio());
        $photoOffice = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $office->getId()]);
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

        $view = new VPrenotazioni();
        $view ->showReservationDetails($reservationwithoffice);
    }

    public function sendreview($idreservation){
        $view = new VRecensioni();
        $view ->formreview($idreservation);
    }

    public function confirmreview($idreservation){
        $value = $_POST['voto'];           // value 1-5
        $comment = $_POST['review']; // comment of review

        $em = getEntityManager();
        $reservation = $em->getRepository(EPrenotazione::class)->find($idreservation);

        $review= new ERecensione();
        $review->setCommento($comment);
        $review->setValutazione((int)$value);
        $review->setPrenotazione($reservation);

        $em->persist($review);
        $em->flush();

        $view = new VRecensioni();
        $view->confirmreview();





    }
}