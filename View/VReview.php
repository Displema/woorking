<?php
namespace View;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../bootstrap.php';
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
    public function showAllReviews($reviews, $office)
    {
        $this->twig->display('/recensioni/recensioni.html.twig', ['reviews' => $reviews,'office' => $office]);
    }
    public function showReviewForm($reservationId)
    {
        $this->twig->display('/recensioni/lasciaunarecensione.html.twig', ['reservation' => $reservationId]);
    }

    public function showReviewConfirmation(): void
    {
        $this->twig->display('/conferme/confermarecensione.html.twig');
    }
}
