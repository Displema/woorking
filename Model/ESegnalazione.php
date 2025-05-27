<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Model\EPrenotazione;
use TechnicalServiceLayer\Repository\ESegnalazioneRepository;

#[ORM\Entity(repositoryClass: ESegnalazioneRepository::class)]
#[ORM\Table(name:"Segnalazioni")]
class ESegnalazione
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity:EPrenotazione::class)]
    //#[ORM\JoinColumn(name:"idPrenotazione", referencedColumnName:"id", nullable: false)]
    private EPrenotazione $prenotazione;
    
    #[ORM\Column]
    private string $commento;

    #[ORM\OneToOne(targetEntity:ERimborso::class, mappedBy: "segnalazione", cascade: ["persist", "remove"])]
    private ?ERimborso $rimborso = null;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPrenotazione(): EPrenotazione
    {
        return $this->prenotazione;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function getRimborso(): ?ERimborso
    {
        return $this->rimborso;
    }


    public function setPrenotazione(EPrenotazione $Prenotazione): ESegnalazione
    {
        $this->prenotazione = $Prenotazione;
        return $this;
    }

    public function setCommento(string $commento): ESegnalazione
    {
        $this->commento = $commento;
        return $this;
    }

    public function setRimborso(?ERimborso $rimborso): ESegnalazione
    {
        $this->rimborso = $rimborso;
        return $this;
    }

    public function __toString(): string
    {
        return "ESegnalazione(ID:". $this->id->__tostring().", ID Prenotazione:". $this->prenotazione->__toString().", Commento: $this->commento)";
    }
}
