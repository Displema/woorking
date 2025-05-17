<?php
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use EPrenotazione;
 #[ORM\Entity]
 #[ORM\Table(name: "Recensione")]

class ERecensione {
    
     #[ORM\Id]
     #[ORM\Column(type:"guid",unique:true)]
     private $id;
    
     #[ORM\OneToOne(targetEntity:EPrenotazione::class)]
     #[ORM\JoinColumn(name:"idPrenotazione", referencedColumnName:"id",nullable:false,unique:true)]
     
    private EPrenotazione $idPrenotazione;

     #[ORM\Column(type:"integer")] 
     
    private $valutazione;
    
     #[ORM\Column(type:"string")]

    private $commento;

    public function __construct( EPrenotazione $idPrenotazione) {
        $this->id = Uuid::uuid4();
        $this->idPrenotazione = $idPrenotazione;
    }

    public function getId(): UuidInterface {
        return $this->id;
    }


    public function getIdPrenotazione(): EPrenotazione {
        return $this->idPrenotazione;
    }

    public function getValutazione(): int {
        return $this->valutazione;
    }

    public function getCommento(): string {
        return $this->commento;
    }

    public function setId(UuidInterface $id): void {
        $this->id = $id;
    }

    public function setIdPrenotazione(EPrenotazione $idPrenotazione): void {
        $this->idPrenotazione = $idPrenotazione;
    }

    public function setValutazione(int $valutazione): void {
        $this->valutazione = $valutazione;
    }

    public function setCommento(string $commento): void {
        $this->commento = $commento;
    }

    public function __toString(): string {
        return "ERecensione(ID:". $this->id->__tostring() .", ID Prenotazione:". $this->idPrenotazione->__toString() .", Valutazione: $this->valutazione, Commento: $this->commento)";
    }
}