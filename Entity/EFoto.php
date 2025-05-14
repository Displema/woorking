<?php

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
/**
 * @ORM\Entity
 * @ORM\Table(name="foto")
 */
class EFoto{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     */
    private UuidInterface $id;
    /** @ORM\Column(type="blob") */
    private $content;

    /** @ORM\Column(type="string") */
    private $mimeType;

    /** @ORM\Column(type="integer") */
    private int $size;

    public function __construct()
    {
        // Genera l'UUID (versione 4)
        $this->id = Uuid::uuid4();  // La libreria Ramsey\Uuid crea un UUID v4
    }

    public function getId(): UuidInterface {
        return $this->id;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function getMimeType(): string {
        return $this->mimeType;
    }

    public function setId(UuidInterface $id): void {
        $this->id = $id;
    }

    public function setContent($content): void {
        $this->content = $content;
    }

    public function setMimetype(string $mimeType): void {
        $this->mimeType = $mimeType;
    }

    public function __toString(): string {
        return "EFoto(ID: $this->id, mimeType: $this->mimeType, Size: $this->size)";
    }
}