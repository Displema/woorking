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
    #[ORM\ManyToOne(targetEntity: EUfficio::class, inversedBy: "serviziAggiuntivi")]
    #[ORM\JoinColumn(name: "idUfficio", referencedColumnName: "id")]
    private EUfficio $Ufficio;

    #[ORM\Id]
    #[ORM\Column(type: "string")]
    private $nomeServizio;

    public function __construct(EUfficio $Ufficio, string $nomeServizio)
    {
        $this->Ufficio = $Ufficio;
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
   
    public function setUfficio(?EUfficio $Ufficio):void
    {
        $this->Ufficio = $Ufficio;
    }

    public function setNomeServizio(string $nomeServizio):void
    {
        $this->nomeServizio = $nomeServizio;
    }

    public function __toString() :string
    {
        return "EServiziAggiuntivi(ID: $this->Ufficio, Nome Servizio: $this->nomeServizio)";
    }
}
