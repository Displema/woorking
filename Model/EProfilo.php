<?php
namespace Model;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use TechnicalServiceLayer\Repository\EProfiloRepository;

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

    #[ORM\Column(name: 'user_id', type: 'string', length: 255, unique: true)]
    private string $user_id;

    #[ORM\OneToMany(targetEntity:ESegnalazione::class, mappedBy:"user", fetch: "EAGER")]
    private Collection $reports;

    #[ORM\OneToMany(targetEntity:EPrenotazione::class, mappedBy:"utente")]
    private Collection $reservations;

    #[ORM\Column(type: 'string', length: 40)]
    private string $name;
    #[ORM\Column(type: 'string', length: 40)]
    private string $surname;
    #[ORM\Column(type: 'string', length: 20)]
    private string $phone;
    #[ORM\Column(type: "date", nullable: false)]
    private DateTime $dob;

    #[ORM\Column(name: "created_at", type: "date", nullable: false)]
    private DateTime $createdAt;

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): EProfilo
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
        $this->createdAt = new \DateTime();
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


    public function __toString(): string
    {
        return "EProfilo(ID: $this->id, Nome: $this->name, Cognome: $this->surname, Telefono: $this->phone, Data di Nascita: " . $this->dob->format('Y-m-d') . ", Admin: " . ('No'.")");
    }
}
