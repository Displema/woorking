<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use TechnicalServiceLayer\Repository\EServiziAggiuntiviRepository;

#[ORM\Entity(repositoryClass: EServiziAggiuntiviRepository::class)]
#[ORM\Table(name: "Servizi_Aggiuntivi")]
class EServiziAggiuntivi
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: EUfficio::class, cascade: ["persist", "remove"], inversedBy: "serviziAggiuntivi")]
    private EUfficio $ufficio;

    #[ORM\Column(name: 'nome_servizio')]
    private string $nomeServizio;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }

    public function getNomeServizio(): string
    {
        return $this->nomeServizio;
    }
   
    public function setUfficio(?EUfficio $ufficio): EServiziAggiuntivi
    {
        $this->ufficio = $ufficio;
        return $this;
    }

    public function setNomeServizio(string $nomeServizio): EServiziAggiuntivi
    {
        $this->nomeServizio = $nomeServizio;
        return $this;
    }

    public function __toString() :string
    {
        return "EServiziAggiuntivi(ID: $this->ufficio, Nome Servizio: $this->nomeServizio)";
    }
}
