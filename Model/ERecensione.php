<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Model\EPrenotazione;
use TechnicalServiceLayer\Repository\ERecensioneRepository;

#[ORM\Entity(repositoryClass: ERecensioneRepository::class)]
 #[ORM\Table(name: "Recensioni")]
class ERecensione
{

     #[ORM\Id]
     #[ORM\Column(type: "uuid", unique: true)]
     #[ORM\GeneratedValue(strategy: "CUSTOM")]
     #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;
    
     #[ORM\ManyToOne(targetEntity:EPrenotazione::class)]
     //#[ORM\JoinColumn(name: "idPrenotazione", referencedColumnName: "id", unique: true, nullable: false)]
    private EPrenotazione $prenotazione;

     #[ORM\Column]
    private int $valutazione;
    
     #[ORM\Column]
    private string $commento;

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

    public function getValutazione(): int
    {
        return $this->valutazione;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function setPrenotazione(EPrenotazione $prenotazione): ERecensione
    {
        $this->prenotazione = $prenotazione;
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
        return "ERecensione(ID:". $this->id->__tostring() .", ID Prenotazione:". $this->prenotazione->__toString() .", Valutazione: $this->valutazione, Commento: $this->commento)";
    }
}
