<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Model\Enum\FasciaOrariaEnum;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Intervalli_disponibilita")]
class EIntervalliDisponibilita
{

    #[Orm\Id]
    #[Orm\Column(type: "guid")]
    private UuidInterface $id;

    #[ORM\ManyToOne(cascade: ["persist", "remove"], inversedBy: "intervalliDisponibilita")]
    private EUfficio $ufficio;

    #[ORM\Column(name: "data_inizio", type: "datetime")]
    private DateTime $dataInizio;

    #[ORM\Column(type:"string", enumType:Enum\FasciaOrariaEnum::class)]
    private FasciaOrariaEnum $fascia;

    #[ORM\Column(name: "data_fine", type: "datetime")]
    private DateTime $dataFine;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }

    public function getDataInizio(): DateTime
    {
        return $this->dataInizio;
    }

    public function getDataFine(): DateTime
    {
        return $this->dataFine;
    }
    
    public function getFascia(): FasciaOrariaEnum
    {
            return $this->fascia;
    }

    public function setUfficio(EUfficio $ufficio): EintervalliDisponibilita
    {
        $this->ufficio = $ufficio;
        return $this;
    }

    public function setDataInizio(DateTime $dataInizio): EintervalliDisponibilita
    {
        $this->dataInizio = $dataInizio;
        return $this;
    }

    public function setDataFine(DateTime $dataFine): EintervalliDisponibilita
    {
        $this->dataFine = $dataFine;
        return $this;
    }

    public function setFascia(FasciaOrariaEnum $fascia): EIntervalliDisponibilita
    {
        $this->fascia = $fascia;
        return $this;
    }

    public function __toString(): string
    {
        return "Intervallo Disponibilità (Ufficio: $this->ufficio, Data Inizio: " . $this->dataInizio->format('Y-m-d H:i:s') . ", Data Fine: " . $this->dataFine->format('Y-m-d H:i:s') . ")";
    }
}
