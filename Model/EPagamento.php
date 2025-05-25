<?php
namespace Model;

use Money\Currency;
use Money\Money;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Pagamenti")]
class EPagamento
{
    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: EPrenotazione::class, inversedBy: "pagamento")]
    private EPrenotazione $prenotazione;

    #[ORM\Column(type:"integer")]
    private int $importo;

    public function __construct(EPrenotazione $prenotazione, int $importo)
    {
        $this->id = Uuid::uuid4();
        $this->prenotazione = $prenotazione;
        $this->setImporto($importo);
        $this->prenotazione->setPagamento($this);  //relazione bidirezionale
    }
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPrenotazione(): EPrenotazione
    {
        return $this->prenotazione;
    }

    public function getImporto(): int
    {
        return $this->importo;
    }

    public function setId(UuidInterface $id): EPagamento
    {
        $this->id = $id;
        return $this;
    }

    public function setPrenotazione(EPrenotazione $prenotazione): EPagamento
    {
        $this->prenotazione = $prenotazione;
        return $this;
    }

    public function setImporto(int $importo): EPagamento
    {
        $this->importo = $importo;
        return $this;
    }

    public function __toString(): string
    {
        return "EPagamento(ID: $this->id, ID Prenotazione: $this->prenotazione, Importo: " . $this->importo . ")";
    }
}
