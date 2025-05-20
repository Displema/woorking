<?php
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Rfc4122\UuidInterface;
require_once __DIR__ . "/EUtente.php";

#[ORM\Entity]
class ELocatore extends EUtente

{
    #[ORM\Column(type: "string", length: 20)]
    private string $partitaIva;

    public function __construct(UuidInterface $id){
        parent::__construct($id);
       
    }

    public function getPartitaIva(): string
    {
        return $this->partitaIva;
    }

    public function setPartitaIva(string $partitaIva): self
    {
        $this->partitaIva = $partitaIva;
        return $this;
    }
}