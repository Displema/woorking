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
    public function showReservation($activeReservations, $pastReservations, $user)
    {
        $this->twig->display(
            '/User/Prenotazioni/Prenotazione.html.twig',
            ['activereservations' => $activeReservations,
            'pastreservations'=>$pastReservations,
            'user'=>$user,
            ]
        );
    }

    public function showReservationDetails($reservation, $user): void
    {
        $this->twig->display('/User/Prenotazioni/VisualizzaPrenotazioni.html.twig', ['reservation' => $reservation,'user'=>$user],);
    }

    public function showAlreadyBookedPage($user): void
    {
        $this->twig->display('/User/conferme/Postinondisponibili.html.twig', ['user'=>$user]);
    }
}
