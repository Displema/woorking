<?php


use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use EUfficio;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "Intervallo_disponibilita")]
class EIntervalliDisponibilita{

    
    private EUfficio $Ufficio;
    private $dataInizio;

    #[ORM\Column(type: "datetime")]
    private $dataFine;

    public function __construct(EUfficio $Ufficio, DateTime $dataInizio, DateTime $dataFine) {
        $this->idUfficio = $Ufficio;
        $this->dataInizio = $dataInizio;
        $this->dataFine = $dataFine;
    }

    public function getUfficio(): EUfficio {
        return $this->Ufficio;
    }

    public function getDataInizio(): DateTime {
        return $this->dataInizio;
    }

    public function getDataFine(): DateTime {
        return $this->dataFine;
    }

    public function setIdUfficio(int $idUfficio): void {
        $this->idUfficio = $idUfficio;
    }

    public function setDataInizio(DateTime $dataInizio): void {
        $this->dataInizio = $dataInizio;
    }

    public function setDataFine(DateTime $dataFine): void {
        $this->dataFine = $dataFine;
    }

    public function __toString(): string {
        return "Intervallo Disponibilità (ID Ufficio: $this->Ufficio, Data Inizio: " . $this->dataInizio->format('Y-m-d H:i:s') . ", Data Fine: " . $this->dataFine->format('Y-m-d H:i:s') . ")";
    }
}