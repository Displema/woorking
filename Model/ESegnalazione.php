<?php
namespace Model;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Model\Enum\ReportStateEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Model\EPrenotazione;
use TechnicalServiceLayer\Repository\ESegnalazioneRepository;

#[ORM\Entity(repositoryClass: ESegnalazioneRepository::class)]
#[ORM\Table(name:"Segnalazioni")]
class ESegnalazione
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity:EUfficio::class, cascade: ["persist",], inversedBy: "segnalazioni")]
    //#[ORM\JoinColumn(name:"idUfficio", referencedColumnName:"id", nullable: false)]
    private EUfficio $ufficio;

    #[ORM\ManyToOne(targetEntity: EProfilo::class, fetch: 'EAGER', inversedBy: "reports")]
    private EProfilo $user;

    #[ORM\Column]
    private string $commento;

    #[ORM\OneToOne(targetEntity:ERimborso::class, mappedBy: "segnalazione", cascade: ["persist", "remove"])]
    private ?ERimborso $rimborso = null;

    #[ORM\Column(type:"string", enumType:Enum\ReportStateEnum::class)]
    private ReportStateEnum $state;

    #[ORM\Column(type:"string", nullable: true)]
    private ?string $commentoAdmin = null;

    public function getCommentoAdmin(): string
    {
        return $this->commentoAdmin;
    }

    public function setCommentoAdmin(string $commentoAdmin): ESegnalazione
    {
        $this->commentoAdmin = $commentoAdmin;
        return $this;
    }
    #[ORM\Column(name: 'created_at')]
    private Datetime $createdAt;

    #[ORM\Column(name: 'updated_at', nullable: true)]
    private ?DateTime $updatedAt = null;

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt ?? null;
    }

    public function setUpdatedAt(DateTime $updatedAt): ESegnalazione
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): ESegnalazione
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getState(): ReportStateEnum
    {
        return $this->state;
    }

    public function setState(ReportStateEnum $state): ESegnalazione
    {
        $this->state = $state;
        return $this;
    }

    // Getter is nullable to avoid runtime errors in twig for uninitialized attributes
    public function getUser(): ?EProfilo
    {
        return $this->user ?? null;
    }

    public function setUser(EProfilo $user): ESegnalazione
    {
        $this->user = $user;
        return $this;
    }

    public function __construct()
    {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }

    public function getCommento(): string
    {
        return $this->commento;
    }

    public function getRimborso(): ?ERimborso
    {
        return $this->rimborso;
    }


    public function setUfficio(EUfficio $Ufficio): ESegnalazione
    {
        $this->ufficio = $Ufficio;
        return $this;
    }

    public function setCommento(string $commento): ESegnalazione
    {
        $this->commento = $commento;
        return $this;
    }

    public function setRimborso(?ERimborso $rimborso): ESegnalazione
    {
        $this->rimborso = $rimborso;
        return $this;
    }

    public function __toString(): string
    {
        return "ESegnalazione(ID:". $this->id->__tostring().", ID Prenotazione:". $this->ufficio->__toString().", Commento: $this->commento)";
    }
}
