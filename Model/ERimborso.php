<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Rimborsi")]
class ERimborso
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\OneToOne(targetEntity: ESegnalazione::class, inversedBy: "rimborso")]
    //#[ORM\JoinColumn(name: "idSegnalazione", referencedColumnName: "id")]
    private ESegnalazione $segnalazione;

    #[ORM\Column]
    private int $importo;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getSegnalazione(): ESegnalazione
    {
        return $this->segnalazione;
    }

    public function getImporto(): int
    {
         return $this->importo;
    }

    public function setSegnalazione(ESegnalazione $Segnalazione): ERimborso
    {
        $this->segnalazione = $Segnalazione;
        return $this;
    }

    public function setImporto(int $importo): ERimborso
    {
        $this->importo = $importo;
        return $this;
    }

    public function __toString(): string
    {
        return "Rimborso(ID: $this->id, ID Segnalazione: $this->segnalazione, Valore: " . $this->importo . ")";
    }
}
