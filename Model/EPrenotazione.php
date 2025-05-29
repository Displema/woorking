<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Model\Enum\UserEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use DateTime;
use TechnicalServiceLayer\Repository\EProfiloRepository;
use TechnicalServiceLayer\Repository\EUtenteRepository;

#[ORM\Entity(repositoryClass: EProfiloRepository::class)]
 #[ORM\Table(name: "Prenotazioni")]
class EPrenotazione
{
     #[ORM\Id]
     #[ORM\Column(type: "uuid", unique: true)]
     #[ORM\GeneratedValue(strategy: "CUSTOM")]
     #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;
    
     #[ORM\ManyToOne]
     //#[ORM\JoinColumn(name:"idUtente", referencedColumnName:"id")]
    private EUfficio $ufficio;
    
     #[ORM\ManyToOne(targetEntity:EProfilo::class)]
    //#[ORM\JoinColumn(name:"idUtente", referencedColumnName:"id")]
    private EProfilo $utente;
    
     #[ORM\Column(type:"string", enumType:Enum\UserEnum::class)]
    private UserEnum $fascia;
    
     #[ORM\Column(type:"datetime")]
    private $data;

    #[ORM\OneToOne(targetEntity:EPagamento::class, mappedBy: "prenotazione", cascade: ["persist", "remove"])]
    private ?EPagamento $pagamento = null;

     #[ORM\OneToOne(targetEntity:ESegnalazione::class, mappedBy: "prenotazione", cascade: ["persist", "remove"])]
    private ?ESegnalazione $segnalazione = null;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }

    public function getUtente(): EProfilo
    {
        return $this->utente;
    }

    public function getFascia(): UserEnum
    {
        return $this->fascia;
    }

    public function getData(): DateTime
    {
        return $this->data;
    }

    public function getPagamento(): ?EPagamento
    {
        return $this->pagamento;
    }

    public function setUfficio(EUfficio $ufficio): EPrenotazione
    {
        $this->ufficio = $ufficio;
        return $this;
    }

    public function setUtente(EProfilo $utente): EPrenotazione
    {
        $this->utente = $utente;
        return $this;
    }

    public function setFascia(UserEnum $fascia): EPrenotazione
    {
        $this->fascia = $fascia;
        return $this;
    }

    public function setData(DateTime $data): EPRenotazione
    {
        $this->data = $data;
        return $this;
    }

    public function setPagamento(?EPagamento $pagamento): EPrenotazione
    {
        $this->pagamento = $pagamento;
        return $this;
    }

    public function __toString(): string
    {
        return "EPrenotazione(ID:". $this->id->__toString() .", ID Ufficio:" .$this->ufficio->__toString()  . ", ID Utente:". $this->utente->__toString().", Fascia:". $this->fascia->value .", Data: " . $this->data->format('Y-m-d H:i:s').")";
    }
}
