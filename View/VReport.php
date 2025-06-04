<?php
namespace View;

class VReport extends BaseView
{
    public function showReportForm($id,$user,$login): void
    {
        $this->twig->display('/segnalazioni/segnalazioni.html.twig', ['idufficio' => $id,'isloggedin'=>$login,'user'=>$user],);
    }

    public function showReportConfirmation($user,$login): void
    {
        $this->twig->display('/conferme/ConfermaSegnalazione.html.twig', ['user' => $user, 'isloggedin' => $login]);
    }
}
