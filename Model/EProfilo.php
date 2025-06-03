<?php
namespace Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use TechnicalServiceLayer\Repository\EProfiloRepository;
use TechnicalServiceLayer\Repository\EUtenteRepository;

#[ORM\Entity(repositoryClass: EProfiloRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["utente" => EProfilo::class, "locatore" => ELocatore::class])]
#[ORM\Table(name: "Profili")]
class EProfilo
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column(name: 'user_id', type: 'string', length: 255)]
    private string $user_id;

    #[ORM\Column(type: 'string', length: 40)]
    private string $name;
    #[ORM\Column(type: 'string', length: 40)]
    private string $surname;
    #[ORM\Column(type: 'string', length: 20)]
    private string $phone;
    #[ORM\Column(type: "date", nullable: false)]
    private DateTime $dob;
    #[ORM\Column(type: "boolean")]
    private bool $admin = false;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getDob(): DateTime
    {
        return $this->dob;
    }

    public function admin(): bool
    {
        return $this->admin;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): EProfilo
    {
        $this->user_id = $user_id;
        return $this;
    }


    public function setName(string $name): EProfilo
    {
        $this->name = $name;
        return $this;
    }

    public function setSurname(string $surname): EProfilo
    {
        $this->surname = $surname;
        return $this;
    }

    public function setPhone(string $phone): EProfilo
    {
        $this->phone = $phone;
        return $this;
    }

    public function setDob(DateTime $dob): EProfilo
    {
        $this->dob = $dob;
        return $this;
    }


    public function setAdmin(bool $admin): EProfilo
    {
        $this->admin = $admin;
        return $this;
    }

    public function __toString(): string
    {
        return "EProfilo(ID: $this->id, Nome: $this->name, Cognome: $this->surname, Telefono: $this->phone, Data di Nascita: " . $this->dob->format('Y-m-d') . ", Admin: " . ($this->admin ? 'Sì' : 'No'.")");
    }
}
