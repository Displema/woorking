<?php
namespace Controller;
use DateTime;
use Model\EFoto;
use Model\EIndirizzo;
use Model\EIntervalloDisponibilita;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\StatoUfficioEnum;
use Model\EPrenotazione;
use Model\EServiziAggiuntivi;
use Model\EUfficio;

use TechnicalServiceLayer\Repository\UserRepository;
use TechnicalServiceLayer\Utility\USession;
use View\VUffici;

class CSearchOffice {



    public function __construct() {
    }

    /**
     * @throws \Exception
     */
    public static function search($luogo, $data, $fascia)

    {
        $em = getEntityManager();
        $repo=$em->getRepository(EUfficio::class);
        //creation of dateobj Datetime with the date in input

        $date = new DateTime($data);
        $uffici = $repo->findbythree($luogo,$date,$fascia);
        $ufficiConFoto = [];
        foreach ($uffici as $ufficio) {
            $fotoBlob = null;
            $fotoEntity = $em->getRepository(\Model\EFoto::class)->findOneBy(['ufficio' => $ufficio->getId()]);

            if ($fotoEntity) {
                $stream = $fotoEntity->getContent();
                $fotoData = is_resource($stream) ? stream_get_contents($stream) : $stream;
                $fotoBlob = 'data:image/jpeg;base64,' . base64_encode($fotoData);
            }

            $ufficiConFoto[] = [
                'ufficio' => $ufficio,
                'fotoBase64' => $fotoBlob,
            ];
        }
            return $ufficiConFoto;



    }




    public function addOffice(){
        $view = new VUffici();
        $view->addOfficeV();
    }


    public function addOfficeInDB(){
        $em = getEntityManager();
        $ufficio = new EUfficio();
        $indirizzo = new Eindirizzo();
        $intervallo = new EIntervalloDisponibilita();

        $indirizzo->setProvincia($_POST['provincia']);
        $indirizzo->setCitta($_POST['comune']);
        $indirizzo->setCap($_POST['cap']);
        $indirizzo->setNumeroCivico($_POST['civico']);
        $indirizzo->setVia($_POST['indirizzo']);

        $ufficio->setTitolo($_POST['nome-ufficio']);
        $ufficio->setPrezzo($_POST['prezzo']);
        $ufficio->setDescrizione($_POST['descrizione']);
        $ufficio->setNumeroPostazioni($_POST['postazioni']);
        $ufficio->setSuperficie($_POST['superficie']);
        $ufficio->setDataCaricamento(new \DateTime());
        $ufficio->setStato(StatoUfficioEnum::InAttesa);
        $ufficio->setIndirizzo($indirizzo);

        $intervallo->setDataInizio(new \DateTime($_POST['data_inizio']));
        $intervallo->setDataFine(new \DateTime($_POST['data_fine']));
        $intervallo->setFascia(FasciaOrariaEnum::from($_POST['fascia']));
        $intervallo->setUfficio($ufficio);

        $em->persist($indirizzo);
        $em->persist($ufficio);
        $em->persist($intervallo);
        $em->flush();

        // Photos
        if (!empty($_FILES['foto']) && isset($_FILES['foto']['tmp_name'])) {

            foreach ($_FILES['foto']['tmp_name'] as $key => $tmpName) {
                if (is_uploaded_file($tmpName)) {
                    $content = file_get_contents($tmpName);
                    $mimeType = $_FILES['foto']['type'][$key];
                    $size = $_FILES['foto']['size'][$key];

                    $foto = new EFoto();
                    $foto->setContent($content);
                    $foto->setMimeType($mimeType);
                    $foto->setSize($size);
                    $foto->setUfficio($ufficio);

                    $em->persist($foto);
                }
            }
            //Salva tutte le foto in una volta sola
            $em->flush();
        }

        // Prendo i servizi dalle checkbox
        var_dump($_POST['servizi']);
        $listaServizi = $_POST['servizi'] ?? [];

       // Se è stato compilato "altro", aggiungo quel servizio
        if (!empty($_POST['altro-servizio'])) {
            $nomeAltro = trim($_POST['altro-servizio']);
            if ($nomeAltro !== '') {
                $listaServizi[] = $nomeAltro;
            }
        }

        // Salvo i servizi nel DB
        foreach ($listaServizi as $nomeServizio) {
            $servizio = new EServiziAggiuntivi();
            $servizio->setNomeServizio($nomeServizio);
            $servizio->setUfficio($ufficio);
            $em->persist($servizio);

        }
        $em->flush();
        header('Location: /uffici');
        exit;

    }

}
