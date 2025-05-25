<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

 #[ORM\Entity]
 #[ORM\Table(name: "Foto")]
class EFoto
{

     #[ORM\Id]
     #[ORM\Column(type: "uuid", unique: true)]
     #[ORM\GeneratedValue(strategy: "CUSTOM")]
     #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;

    #[ORM\Column(type:"blob")]
    private $content;

    #[ORM\ManyToOne(targetEntity: EUfficio::class, cascade: ["persist", "remove"], inversedBy: "foto")]
    private EUfficio $ufficio;

    #[ORM\Column(name: "mime_type")]
    private string $mimeType;

    #[ORM\Column]
    private int $size;

    public function __construct()
    {
        // Genera l'UUID (versione 4)
        //$this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getUfficio(): EUfficio
    {
        return $this->ufficio;
    }
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getSize(): int
    {
        return $this->size;
    }
    public function setContent($content): EFoto
    {
        $this->content = $content;
        return $this;
    }
    public function setUfficio(?EUfficio $ufficio): EFoto
    {
        $this->ufficio = $ufficio;
        return $this;
    }

    public function setMimetype(string $mimeType): EFoto
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    public function setSize(int $size): EFoto
    {
        $this->size = $size;
        return $this;
    }
    public function __toString(): string
    {
        return "EFoto(ID: $this->id, mimeType: $this->mimeType, Size: $this->size)";
    }
}
