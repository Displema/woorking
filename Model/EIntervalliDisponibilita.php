<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: "Intervallo_disponibilita")]
class EIntervalliDisponibilita
{

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: EUfficio::class)]
    #[ORM\JoinColumn(name: "idUfficio", referencedColumnName: "id")]
    private EUfficio $Ufficio;

    #[ORM\Id]
    #[ORM\Column(type: "datetime")]
    private DateTime $dataInizio;

    #[ORM\Column(type:"string", enumType:Enum\FasciaPrenotazione::class)]
    private FasciaPrenotazione $fascia;

    #[ORM\Column(type: "datetime")]
    private DateTime $dataFine;

    public function __construct(EUfficio $Ufficio, DateTime $dataInizio, DateTime $dataFine, FasciaPrenotazione $fascia)
    {
        $this->Ufficio = $Ufficio;
        $this->dataInizio = $dataInizio;
        $this->dataFine = $dataFine;
        $this->fascia = $fascia;
    }

    public function getUfficio(): EUfficio
    {
        return $this->Ufficio;
    }

    public function getDataInizio(): DateTime
    {
        return $this->dataInizio;
    }

    public function getDataFine(): DateTime
    {
        return $this->dataFine;
    }
    
    public function getFascia(): FasciaPrenotazione
    {
            return $this->fascia;
    }

    public function setUfficio(EUfficio $Ufficio): void
    {
        $this->Ufficio = $Ufficio;
    }

    public function setDataInizio(DateTime $dataInizio): void
    {
        $this->dataInizio = $dataInizio;
    }

    public function setDataFine(DateTime $dataFine): void
    {
        $this->dataFine = $dataFine;
    }

    public function setfascia(FasciaPrenotazione $fascia):void
    {
        $this->fascia = $fascia;
    }

    public function __toString(): string
    {
        return "Intervallo Disponibilità (ID Ufficio: $this->Ufficio, Data Inizio: " . $this->dataInizio->format('Y-m-d H:i:s') . ", Data Fine: " . $this->dataFine->format('Y-m-d H:i:s') . ")";
    }
}
