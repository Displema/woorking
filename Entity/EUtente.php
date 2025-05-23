<?php
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "tipo", type: "string")]
#[ORM\DiscriminatorMap(["utente" =>EUtente::class, "Locatore" => ELocatore::class])]
class EUtente{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private String $id;
    #[ORM\Column(type: 'string', length: 40, unique: true)]
    private string $nome;
    #[ORM\Column(type: 'string', length: 40, unique: true)]
    private string $cognome;
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $password;
    #[ORM\Column(type: 'string', length: 20, unique: true)]
    private string $telefono;
    #[ORM\Column(type: "date", nullable: false)]
    private DateTime $dataNascita;
    #[ORM\Column(type: "boolean")]
    private bool $isAdmin;

    public function __construct(UuidInterface $id) {
        $this->id = Uuid::uuid4();
    }
    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getCognome(): string {
        return $this->cognome;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function getDataNascita(): DateTime {
        return $this->dataNascita;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getisAdmin(): bool {
        return $this->isAdmin;
    }

    public function setId(UuidInterface $id): void {
        $this->id = $id;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setCognome(string $cognome): void {
        $this->cognome = $cognome;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTelefono(string $telefono): void {
        $this->telefono = $telefono;
    }

    public function setDataNascita(DateTime $dataNascita): void {
        $this->dataNascita = $dataNascita;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function setIsAdmin(bool $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }

    public function __toString(): string {
        return "EUtente(ID: $this->id, Nome: $this->nome, Cognome: $this->cognome, Email: $this->email, Telefono: $this->telefono, Data di Nascita: " . $this->dataNascita->format('Y-m-d') . ", Admin: " . ($this->isAdmin ? 'Sì' : 'No'.")");
    }
}