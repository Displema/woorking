<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
use Model\Enum\FasciaOrariaEnum;
use Model\Enum\UserEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TechnicalServiceLayer\Repository\EIntervalloDisponibilitaRepository;

#[ORM\Entity(repositoryClass: EIntervalloDisponibilitaRepository::class)]
#[ORM\Table(name: "Intervalli_disponibilita")]
class EIntervalloDisponibilita
{

    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
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
        //$this->id = Uuid::uuid4();
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

    public function setUfficio(EUfficio $ufficio): EIntervalloDisponibilita
    {
        $this->ufficio = $ufficio;
        return $this;
    }

    public function setDataInizio(DateTime $dataInizio): EIntervalloDisponibilita
    {
        $this->dataInizio = $dataInizio;
        return $this;
    }

    public function setDataFine(DateTime $dataFine): EIntervalloDisponibilita
    {
        $this->dataFine = $dataFine;
        return $this;
    }

    public function setFascia(FasciaOrariaEnum $fascia): EIntervalloDisponibilita
    {
        $this->fascia = $fascia;
        return $this;
    }

    public function __toString(): string
    {
        return "Intervallo Disponibilità (Ufficio: $this->ufficio, Data Inizio: " . $this->dataInizio->format('Y-m-d H:i:s') . ", Data Fine: " . $this->dataFine->format('Y-m-d H:i:s') . ")";
    }
}
