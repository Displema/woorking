<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Rimborsi")]
class ERimborso
{
    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: ESegnalazione::class, inversedBy: "rimborso")]
    #[ORM\JoinColumn(name: "idSegnalazione", referencedColumnName: "id")]
    private ESegnalazione $Segnalazione;

    #[ORM\Column]
    private int $importo;

    public function __construct(ESegnalazione $Segnalazione)
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getSegnalazione(): ESegnalazione
    {
        return $this->Segnalazione;
    }

    public function getImporto(): int
    {
         return $this->importo;
    }

    public function setId(UuidInterface $id): ERimborso
    {
        $this->id = $id;
        return $this;
    }

    public function setSegnalazione(ESegnalazione $Segnalazione): ERimborso
    {
        $this->Segnalazione = $Segnalazione;
        return $this;
    }

    public function setImporto(int $importo): ERimborso
    {
        $this->importo = $importo;
        return $this;
    }

    public function __toString(): string
    {
        return "Rimborso(ID: $this->id, ID Segnalazione: $this->Segnalazione, Valore: " . $this->importo . $this->valuta . ")";
    }
}
