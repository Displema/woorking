<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use TechnicalServiceLayer\Repository\ELocatoreRepository;
use TechnicalServiceLayer\Repository\EUtenteRepository;

#[ORM\Entity(repositoryClass: ELocatoreRepository::class)]
#[ORM\Table(name: "Profili_Locatori")]
class ELocatore extends EProfilo
{

    // The length of italian partita iva is of 11 characters, we're leaving some wiggle room for edge cases.
    #[ORM\Column(name: "partita_iva", type: "string", length: 15, nullable: false)]
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
