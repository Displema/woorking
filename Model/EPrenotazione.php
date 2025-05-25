<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Model\Enum\FasciaOrariaEnum;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

use DateTime;

 #[ORM\Entity]
 #[ORM\Table(name: "Prenotazioni")]
class EPrenotazione
{
      #[ORM\Id]
      #[ORM\Column(type:"guid", unique:true)]
    private string $id;
    
     #[ORM\ManyToOne(targetEntity:EUfficio::class)]
     #[ORM\JoinColumn(name:"idUfficio", referencedColumnName:"id")]
     
    private EUfficio $idUfficio;
    
     #[ORM\ManyToOne(targetEntity:EProfilo::class)]
     #[ORM\JoinColumn(name:"idUtente", referencedColumnName:"id")]
     
    private EProfilo $idUtente;
    
     #[ORM\Column(type:"string", enumType:Enum\FasciaOrariaEnum::class)]
     
    private FasciaOrariaEnum $fascia;
    
     #[ORM\Column(type:"datetime")]
    
    private $data;

    #[ORM\OneToOne(targetEntity:EPagamento::class, mappedBy: "prenotazione", cascade: ["persist", "remove"])]
    private ?EPagamento $pagamento = null;

    public function __construct(EUfficio $idUfficio, EProfilo $idUtente)
    {
        $this->id = Uuid::uuid4();
        $this->idUfficio = $idUfficio;
        $this->idUtente = $idUtente;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIdUfficio(): EUfficio
    {
        return $this->idUfficio;
    }

    public function getIdUtente(): EProfilo
    {
        return $this->idUtente;
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



    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function setIdUfficio(EUfficio $idUfficio): void
    {
        $this->idUfficio = $idUfficio;
    }

    public function setIdUtente(EProfilo $idUtente): void
    {
        $this->idUtente = $idUtente;
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
        return "EPrenotazione(ID:". $this->id->__toString() .", ID Ufficio:" .$this->idUfficio->__toString()  . ", ID Utente:". $this->idUtente->__toString().", Fascia:". $this->fascia->value .", Data: " . $this->data->format('Y-m-d H:i:s').")";
    }
}
