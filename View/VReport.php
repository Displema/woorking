<?php
namespace View;

class VReport extends BaseView
{
    public function showReportForm($id): void
    {
        $this->twig->display('/segnalazioni/segnalazioni.html.twig', ['idufficio' => $id]);
    }

    public function showReportConfirmation(): void
    {
        $this->twig->display('/conferme/ConfermaSegnalazione.html.twig');
    }
}
