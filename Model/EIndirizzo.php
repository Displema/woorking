<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
#[ORM\Table(name: "Indirizzi")]
class EIndirizzo
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column]
    private string $via;

    #[ORM\Column(name: "numero_civico")]
    private string $numeroCivico;

    #[ORM\Column]
    private string $citta;

    #[ORM\Column(length: 2)]
    private string $provincia;

    #[ORM\Column(length: 5)]
    private string $cap;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();  // Genera un UUID (versione 4)
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getVia(): string
    {
        return $this->via;
    }

    public function getNumeroCivico(): string
    {
        return $this->numeroCivico;
    }

    public function getCitta(): string
    {
        return $this->citta;
    }

    public function getProvincia(): string
    {
        return $this->provincia;
    }

    public function getCap(): string
    {
        return $this->cap;
    }

    public function setVia(string $via): EIndirizzo
    {
        $this->via = $via;
        return $this;
    }

    public function setNumeroCivico(string $numeroCivico): EIndirizzo
    {
        $this->numeroCivico = $numeroCivico;
        return $this;
    }

    public function setCitta(string $citta): EIndirizzo
    {
        $this->citta = $citta;
        return $this;
    }

    public function setProvincia(string $provincia): EIndirizzo
    {
        $this->provincia = $provincia;
        return $this;
    }

    public function setCap(string $cap): EIndirizzo
    {
        $this->cap = $cap;
        return $this;
    }

    public function __toString(): string
    {
        return "Indirizzo: {$this->via} {$this->numeroCivico}, {$this->citta}, {$this->provincia}, {$this->cap}";
    }
}
