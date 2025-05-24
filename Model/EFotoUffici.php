<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

 #[ORM\Entity]
 #[ORM\Table(name:"FotoUffici")]

class EFotoUffici
{
    
     #[ORM\Id]
     #[ORM\Column(type:"guid")]
     
    private UuidInterface $idFoto;
    
     #[ORM\Id]
     #[ORM\Column(type:"guid")]
    private UuidInterface $idUfficio;

    public function __construct()
    {
        $this->idFoto = Uuid::uuid4();
        $this->idUfficio = Uuid::uuid4();
    }

    public function getIdFoto(): UuidInterface
    {
        return $this->idFoto;
    }

    public function getIdUfficio(): UuidInterface
    {
        return $this->idUfficio;
    }

    public function setIdFoto(UuidInterface $idFoto): void
    {
        $this->idFoto = $idFoto;
    }

    public function setIdUfficio(UuidInterface $idUfficio): void
    {
        $this->idUfficio = $idUfficio;
    }

    public function __toString(): string
    {
        return "EFotoUfficio(ID Foto:". $this->idFoto->toString() .", ID Ufficio:". $this->idUfficio->toString() .")";
    }
}
