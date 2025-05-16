<?php
use Money\Money;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


#[ORM\Entity]
#[ORM\Table(name: "Rimborsi")]
class ERimborso{

    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: ESegnalazione::class, inversedBy: "rimborso")]
    #[ORM\JoinColumn(name: "idSegnalazione", referencedColumnName: "id")]
    private ESegnalazione $Segnalazione;

    #[ORM\Column(type:"integer")]
    private $importo;

    #[ORM\Column(type:"string")]
    private $valuta;

    public function __construct(ESegnalazione $Segnalazione,  Money $importo) {
        $this->id = Uuid::uuid4();
        $this->Segnalazione = $Segnalazione;
        $this->setImporto($importo);
    }

    public function getId(): UuidInterface{
        return $this->id;
    }

    public function getSegnalazione(): ESegnalazione{
        return $this->Segnalazione;
    }

    public function getImporto(): Money{
         return new Money($this->importo, new \Money\Currency($this->valuta));
    }

    public function setId(UuidInterface $id): void{
        $this->id = $id;
    }

    public function setSegnalazione(ESegnalazione $Segnalazione): void{
        $this->Segnalazione = $Segnalazione;
    }

    public function setImporto(Money $importo): void{
        $this->importo =(int) $importo->getAmount();
        $this->valuta = $importo->getCurrency()->getCode();
    }

    public function __toString(): string{
        return "Rimborso(ID: $this->id, ID Segnalazione: $this->Segnalazione, Valore: " . $this->importo . $this->valuta . ")";
    }

}