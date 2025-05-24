<?php
namespace Model\Enum;

enum StatoUfficio : string
{
    case InAttesa = " In attesa";

    case Approvato = "Approvato";

    case NonApprovato = "Non Approvato";
}
