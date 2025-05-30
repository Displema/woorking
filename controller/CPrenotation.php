<?php
namespace Controller;

use DateTime;
use Model\EFoto;
use Model\EPrenotazione;

use Model\EUfficio;
use View\VPrenotazioni;



class CPrenotation
{

    public function showPrenotation()
    {
        $today = new DateTime();
        $em = getEntityManager();
        $repository = $em->getRepository(EPrenotazione::class);
        $prenotations = $repository->findAll();
        $prenotationWithOffice = [];
        $oldprenotationWithOffice = [];

        foreach ($prenotations as $prenotation) {
            $idOffice = $prenotation->getUfficio();
            $photo = $em->getRepository(EFoto::class)->findOneBy(['ufficio' => $idOffice]);
            $office = $em->getRepository(EUfficio::class)->find($idOffice);

            if ($prenotation->getData() >= $today) {
                if ($photo) {
                    $idPhoto = $photo->getId();

                    $photoUrl = "/static/img/" . $idPhoto;

                }
                $prenotationWithOffice[] = [
                    'prenotazione' => $prenotation,
                    'ufficio' => $office,
                    'photo' => $photoUrl
                ];
            } else {
                if ($photo) {
                    $idPhoto = $photo->getId();

                    $photoUrl = "/static/img/" . $idPhoto;

                }
                $oldprenotationWithOffice[] = [
                    'prenotazione' => $prenotation,
                    'ufficio' => $office,
                    'photo' => $photoUrl
                ];
            }


        }

        $view = new VPrenotazioni();
        $view->showPrenotation($prenotationWithOffice, $oldprenotationWithOffice);

    }

    public function showPrenotationDetails($id)
    {
        $prenotationwithoffice = [];

        $em = getEntityManager();
        $repository = $em->getRepository(EPrenotazione::class);
        $prenotation = $repository->find($id);
        $fotoUrl = [];
        $ufficio = $em->getRepository(EUfficio::class)->find($prenotation->getUfficio());
        $fotoUfficio = $em->getRepository(\Model\EFoto::class)->findBy(['ufficio' => $ufficio]);
        foreach ($fotoUfficio as $foto) {
            if ($foto) {
                $idPhoto = $foto->getId();
                $fotoUrl[] = "/static/img/" . $idPhoto;

            }


        }
        $prenotationwithoffice[] = [
            'ufficio' => $ufficio,
            'foto' => $fotoUrl,
            'prenotazione'=>$prenotation
        ];

        $view = new VPrenotazioni();
        $view ->showPrenotationDetails($prenotationwithoffice);
    }
}