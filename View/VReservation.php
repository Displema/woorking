<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
use Doctrine\Common\Collections\Collection;
use Model\EPrenotazione;

class VReservation extends BaseView
{
    /**
     * @param array $activeReservations
     * @param array $pastReservations
     * @return void
     */
    public function showUserReservations($activeReservations, $pastReservations, $user): void
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

    public function showAdminReservations($activeReservations, $pastReservations, $user): void
    {
        $this->twig->display(
            '/admin/reservations/reservations.html.twig',
            ['activeReservations'=>$activeReservations,
                'pastReservations'=>$pastReservations,
            'user'=>$user,
            ]
        );
    }
    public function searchReservations($activeReservations, $pastReservations, $user)
    {

        $this->twig->display('/landlord/prenotazioni/gestione_prenotazioni_locatore.html.twig', ['activeReservations'=>$activeReservations,
            'pastReservations'=>$pastReservations,
            'user'=>$user,
            ]);
    }
}
