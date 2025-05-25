<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Model\EPrenotazione;

#[ORM\Entity]
#[ORM\Table(name:"Segnalazioni")]
class ESegnalazione
{
    #[ORM\Id]
    #[ORM\Column(type:"guid", unique: true)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity:EPrenotazione::class)]
    #[ORM\JoinColumn(name:"idPrenotazione", referencedColumnName:"id", nullable: false)]
    private EPrenotazione $Prenotazione;
    
    #[ORM\Column(type:"string", nullable: false)]
    private $commento;

    #[ORM\OneToOne(targetEntity:ERimborso::class, mappedBy: "Segnalazione", cascade: ["persist", "remove"])]
    private ?ERimborso $rimborso = null;

    public function __construct(EPrenotazione $Prenotazione, string $commento)
    {
        $this->id = Uuid::uuid4();
        $this->Prenotazione = $Prenotazione;
        $this->commento = $commento;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPrenotazione(): EPrenotazione
    {
        return $this->Prenotazione;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function getRimborso(): ?ERimborso
    {
        return $this->rimborso;
    }

    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function setPrenotazione(EPrenotazione $Prenotazione): ESegnalazione
    {
        $this->Prenotazione = $Prenotazione;
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
        return "ESegnalazione(ID:". $this->id->__tostring().", ID Prenotazione:". $this->Prenotazione->__toString().", Commento: $this->commento)";
    }
}
