<?php
namespace View;

use \controller\COffice;
use Doctrine\Common\Collections\Collection;
use Model\ERecensione;
use Model\EUfficio;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VReview extends BaseView
{
    /**
     * @param Collection<int,ERecensione> $reviews
     * @param EUfficio $office
     * @return void
     */
    public function showAllReviews($reviews, $office, $user)
    {
        $this->twig->display('/User/recensioni/recensioni.html.twig', ['reviews' => $reviews,'office' => $office,'user' => $user]);
    }
    public function showReviewForm($reservationId, $user)
    {
        $this->twig->display('/User/recensioni/lasciaunarecensione.html.twig', ['idReservation' => $reservationId,'user' => $user ]);
    }

    public function showReviewConfirmation($user): void
    {
        $this->twig->display('/User/conferme/confermarecensione.html.twig', ['user' => $user]);
    }

    public function reviews($array) : void
    {
        $this->twig->display('/landlord/recensioni/recensioni.html.twig', ['recensioni' => $array]);
    }
}
