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

    #[ORM\ManyToOne(targetEntity:EUfficio::class,cascade: ["persist",], inversedBy: "segnalazioni")]
    //#[ORM\JoinColumn(name:"idUfficio", referencedColumnName:"id", nullable: false)]
    private EUfficio $ufficio;
    
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

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function getRimborso(): ?ERimborso
    {
        return $this->rimborso;
    }


    public function setUfficio(EUfficio $Ufficio): ESegnalazione
    {
        $this->ufficio = $Ufficio;
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
        return "ESegnalazione(ID:". $this->id->__tostring().", ID Prenotazione:". $this->ufficio->__toString().", Commento: $this->commento)";
    }
}
