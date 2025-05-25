<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "Servizi_Aggiuntivi")]
class EServiziAggiuntivi
{
    #[ORM\Id]
    private UuidInterface $id;

    #[ORM\ManyToOne(targetEntity: EUfficio::class, cascade: ["persist", "remove"], inversedBy: "serviziAggiuntivi")]
    private EUfficio $ufficio;

    #[ORM\Column]
    private string $nomeServizio;

    public function __construct(EUfficio $ufficio, string $nomeServizio)
    {
        $this->ufficio = $ufficio;
        $this->nomeServizio = $nomeServizio;
    }

    public function getUfficio(): EUfficio
    {
        return $this->Ufficio;
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
        return "EServiziAggiuntivi(ID: $this->Ufficio, Nome Servizio: $this->nomeServizio)";
    }
}
