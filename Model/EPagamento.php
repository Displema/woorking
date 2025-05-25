<?php
namespace Model;

use Money\Currency;
use Money\Money;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Pagamenti")]
class EPagamento
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: EPrenotazione::class, inversedBy: "pagamento")]
    private EPrenotazione $prenotazione;

    #[ORM\Column(type:"integer")]
    private int $importo;

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

    public function getImporto(): int
    {
        return $this->importo;
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
