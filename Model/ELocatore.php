<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class ELocatore extends EUtente
{

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private string $partitaIva;

    public function getPartitaIva(): string
    {
        return $this->partitaIva;
    }

    public function setPartitaIva(string $partitaIva): void
    {
        $this->partitaIva = $partitaIva;
    }

    public function __toString(): string
    {
        return parent::__toString() . ", Partita IVA: $this->partitaIva";
    }
}
