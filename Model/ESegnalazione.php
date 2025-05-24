<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use EPrenotazione;

#[ORM\Entity]
#[ORM\Table(name:"Segnalazione")]
class ESegnalazione{
    #[ORM\Id]
    #[ORM\Column(type:"guid",unique: true)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity:EPrenotazione::class)]
    #[ORM\JoinColumn(name:"idPrenotazione", referencedColumnName:"id", nullable: false)]
    private EPrenotazione $Prenotazione;
    
    #[ORM\Column(type:"string", nullable: false)]
    private $commento;

    #[ORM\OneToOne(targetEntity:ERimborso::class, mappedBy: "Segnalazione", cascade: ["persist", "remove"])]
    private ?ERimborso $rimborso = null;

    public function __construct(EPrenotazione $Prenotazione, string $commento) {
        $this->id = Uuid::uuid4();
        $this->Prenotazione = $Prenotazione;
        $this->commento = $commento;
    }

    public function getId(): UuidInterface{
        return $this->id;
    }

    public function getIdPrenotazione(): EPrenotazione{
        return $this->Prenotazione;
    }

    public function getCommento(): string{
        return $this->commento;
    }

    public function getRimborso(): ?ERimborso{
        return $this->rimborso;
    }

    public function setId(UuidInterface $id): void{
        $this->id = $id;
    }

    public function setIdPrenotazione( EPrenotazione $Prenotazione): void{
        $this->Prenotazione = $Prenotazione;
    }

    public function setCommento(string $commento): void{
        $this->commento = $commento;
    }

    public function setRimborso(?ERimborso $rimborso): void{
        $this->rimborso = $rimborso;
    }

    public function __toString(): string{
        return "ESegnalazione(ID:". $this->id->__tostring().", ID Prenotazione:". $this->Prenotazione->__toString().", Commento: $this->commento)";
    }

}