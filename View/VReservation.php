<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use Doctrine\Common\Collections\Collection;
use Model\EPrenotazione;

class VReservation extends BaseView
{
    /**
     * @param Collection<int, EPrenotazione> $activeReservations
     * @param Collection<int, EPrenotazione> $pastReservations
     * @return void
     */
    public function showReservation($activeReservations, $pastReservations)
    {
        $this->twig->display(
            '/Prenotazioni/Prenotazione.html.twig',
            ['reservations' => $activeReservations,
            'oldreservations'=>$pastReservations]
        );
    }

    public function showReservationDetails( $reservation): void
    {
        $this->twig->display('Prenotazioni/VisualizzaPrenotazioni.html.twig', ['reservations' => $reservation]);
    }

    public function showAlreadyBookedPage(): void
    {
        $this->twig->display('/conferme/Postinondisponibili.html.twig');
    }
}
