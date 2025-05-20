<?php



use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "indirizzo")]
class EIndirizzo {

    #[ORM\Id]
    #[ORM\Column(type: "guid", unique: true)]
    private string $id;

    #[ORM\Column(type: "string")]
    private $via;

    #[ORM\Column(type:"string")]
    private $numeroCivico;

    #[ORM\Column(type:"string")]
    private $citta;

    #[ORM\Column(type:"string")]
    private $provincia;

    #[ORM\Column(type:"string")]
    private $cap;

    public function __construct() {
        $this->id = Uuid::uuid4();  // Genera un UUID (versione 4)
        
    }

    public function getId(): string {
        return $this->id;
    }

    public function getVia(): string {
        return $this->via;
    }

    public function getNumeroCivico(): string {
        return $this->numeroCivico;
    }

    public function getCitta(): string {
        return $this->citta;
    }

    public function getProvincia(): string {
        return $this->provincia;
    }

    public function getCap(): string {
        return $this->cap;
    }

    public function setId(UuidInterface $id): void {
        $this->id = $id;
  }

    public function setVia(string $via): void {
        $this->via = $via;
    }

    public function setNumeroCivico(string $numeroCivico): void {
        $this->numeroCivico = $numeroCivico;
    }

    public function setCitta(string $citta): void {
        $this->citta = $citta;
    }

    public function setProvincia(string $provincia): void {
        $this->provincia = $provincia;
    }

    public function setCap(string $cap): void {
        $this->cap = $cap;
    }

    public function __toString(): string {
        return "Indirizzo: {$this->via} {$this->numeroCivico}, {$this->citta}, {$this->provincia}, {$this->cap}";
    }
}

