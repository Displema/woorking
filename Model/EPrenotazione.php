<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Model\Enum\FasciaOrariaEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use DateTime;

 #[ORM\Entity]
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
    
     #[ORM\Column(type:"string", enumType:Enum\FasciaOrariaEnum::class)]
    private FasciaOrariaEnum $fascia;
    
     #[ORM\Column(type:"datetime")]
    private $data;

    #[ORM\OneToOne(targetEntity:EPagamento::class, mappedBy: "prenotazione", cascade: ["persist", "remove"])]
    private ?EPagamento $pagamento = null;

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

    public function getFascia(): FasciaOrariaEnum
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

    public function setUfficio(EUfficio $ufficio): void
    {
        $this->ufficio = $ufficio;
    }

    public function setUtente(EProfilo $utente): void
    {
        $this->utente = $utente;
    }

    public function setFascia(FasciaOrariaEnum $fascia): void
    {
        $this->fascia = $fascia;
    }

    public function setData(DateTime $data): void
    {
        $this->data = $data;
    }

    public function setPagamento(?EPagamento $pagamento): void
    {
        $this->pagamento = $pagamento;
    }

    public function __toString(): string
    {
        return "EPrenotazione(ID:". $this->id->__toString() .", ID Ufficio:" .$this->ufficio->__toString()  . ", ID Utente:". $this->utente->__toString().", Fascia:". $this->fascia->value .", Data: " . $this->data->format('Y-m-d H:i:s').")";
    }
}
