<?php
namespace Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "tipo", type: "string")]
#[ORM\DiscriminatorMap(["utente" => EProfilo::class, "locatore" => ELocatore::class])]
#[ORM\Table(name: "Profili")]
class EProfilo
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private UuidInterface $id;

    #[ORM\Column(name: 'id_utente', type: 'string', length: 255)]
    private string $idUtente;

    #[ORM\Column(type: 'string', length: 40)]
    private string $nome;
    #[ORM\Column(type: 'string', length: 40)]
    private string $cognome;
    #[ORM\Column(type: 'string', length: 20)]
    private string $telefono;
    #[ORM\Column(type: "date", nullable: false)]
    private DateTime $dataNascita;
    #[ORM\Column(type: "boolean")]
    private bool $isAdmin;

    public function __construct(UuidInterface $id)
    {
        $this->id = Uuid::uuid4();
    }
    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCognome(): string
    {
        return $this->cognome;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getDataNascita(): DateTime
    {
        return $this->dataNascita;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function getIdUtente(): string
    {
        return $this->idUtente;
    }

    public function setIdUtente(string $idUtente): EProfilo
    {
        $this->idUtente = $idUtente;
        return $this;
    }


    public function setNome(string $nome): EProfilo
    {
        $this->nome = $nome;
        return $this;
    }

    public function setCognome(string $cognome): EProfilo
    {
        $this->cognome = $cognome;
        return $this;
    }

    public function setTelefono(string $telefono): EProfilo
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function setDataNascita(DateTime $dataNascita): EProfilo
    {
        $this->dataNascita = $dataNascita;
        return $this;
    }


    public function setIsAdmin(bool $isAdmin): EProfilo
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function __toString(): string
    {
        return "EProfilo(ID: $this->id, Nome: $this->nome, Cognome: $this->cognome, Telefono: $this->telefono, Data di Nascita: " . $this->dataNascita->format('Y-m-d') . ", Admin: " . ($this->isAdmin ? 'Sì' : 'No'.")");
    }
}
