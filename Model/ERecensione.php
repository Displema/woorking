<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Model\EPrenotazione;

 #[ORM\Entity]
 #[ORM\Table(name: "Recensioni")]
class ERecensione
{
    
     #[ORM\Id]
     #[ORM\Column(type:"guid", unique:true)]
     private string $id;
    
     #[ORM\OneToOne(targetEntity:EPrenotazione::class)]
     #[ORM\JoinColumn(name: "idPrenotazione", referencedColumnName: "id", unique: true, nullable: false)]
    private EPrenotazione $idPrenotazione;

     #[ORM\Column]
    private int $valutazione;
    
     #[ORM\Column]

    private string $commento;

    public function __construct(EPrenotazione $idPrenotazione)
    {
        $this->id = Uuid::uuid4();
        $this->idPrenotazione = $idPrenotazione;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }


    public function getIdPrenotazione(): EPrenotazione
    {
        return $this->idPrenotazione;
    }

    public function getValutazione(): int
    {
        return $this->valutazione;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function setId(UuidInterface $id): ERecensione
    {
        $this->id = $id;
        return $this;
    }

    public function setIdPrenotazione(EPrenotazione $idPrenotazione): ERecensione
    {
        $this->idPrenotazione = $idPrenotazione;
        return $this;
    }

    public function setValutazione(int $valutazione): ERecensione
    {
        $this->valutazione = $valutazione;
        return $this;
    }

    public function setCommento(string $commento): ERecensione
    {
        $this->commento = $commento;
        return $this;
    }

    public function __toString(): string
    {
        return "ERecensione(ID:". $this->id->__tostring() .", ID Prenotazione:". $this->idPrenotazione->__toString() .", Valutazione: $this->valutazione, Commento: $this->commento)";
    }
}
