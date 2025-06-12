<?php
namespace Model\Enum;

enum StatoUfficioEnum : string
{
    case InAttesa = " In attesa";

    case Approvato = "Approvato";

    case NonApprovato = "Non Approvato";

    case Nascosto = "Nascosto";
}
