<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Profili_Locatori")]
class ELocatore extends EProfilo
{

    #[ORM\Column(type: "string", length: 20, nullable: false)]
    private string $partitaIva;

    public function getPartitaIva(): string
    {
        return $this->partitaIva;
    }

    public function setPartitaIva(string $partitaIva): ELocatore
    {
        $this->partitaIva = $partitaIva;
        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . ", Partita IVA: $this->partitaIva";
    }
}
