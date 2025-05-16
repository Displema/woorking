<?php
use Money\Money;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


#[ORM\Entity]
#[ORM\Table(name: "Pagamenti")]
class EPagamento{

    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private  UuidInterface $id;

    #[ORM\OneToOne(targetEntity: EPrenotazione::class)]
    #[ORM\JoinColumn(name: "idPrenotazione", referencedColumnName: "id")]
    private EPrenotazione $Prenotazione;

    #[ORM\Column(type:"integer")]
    private $importo;

    #[ORM\Column(type:"string")]
    private $valuta;

    public function __construct(EPrenotazione $Prenotazione, Money $importo) {
        $this->id = Uuid::uuid4();
        $this->Prenotazione = $Prenotazione;
        $this->setImporto($importo);
    }
    public function getId(): UuidInterface{
        return $this->id;
    }

    public function getPrenotazione(): EPrenotazione{
        return $this->Prenotazione;
    }

    public function getImporto(): Money{
        return new Money($this->importo, new \Money\Currency($this->valuta));
    }

    public function setId(UuidInterface $id): void{
        $this->id = $id;
    }

    public function setPrenotazione(EPrenotazione $Prenotazione): void{
        $this->Prenotazione = $Prenotazione;
    }

    public function setImporto(Money $importo): void{
        $this->importo =(int) $importo->getAmount();
        $this->valuta = $importo->getCurrency()->getCode();
    }

    public function __toString(): string{
        return "EPagamento(ID: $this->id, ID Prenotazione: $this->Prenotazione, Importo: " . $this->importo . $this->valuta . ")";
    }


}