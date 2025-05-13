<?php
class EFoto{
    private $id;
    private $data; //tipo blob rappresentato come string
    private $filename;
    private $extension;

    public function __construct(int $id, string $data, string $filename, string $extension) {
        $this->id = $id;
        $this->data = $data; //dati binari dell'immagine
        $this->filename = $filename;
        $this->extension = $extension;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getData(): string {
        return $this->data;
    }

    public function getFilename(): string {
        return $this->filename;
    }

    public function getExtension(): string {
        return $this->extension;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setData(string $data): void {
        $this->data = $data;
    }

    public function setFilename(string $filename): void {
        $this->filename = $filename;
    }

    public function setExtension(string $extension): void {
        $this->extension = $extension;
    }

    public function __toString(): string {
        return "EFoto(ID: $this->id, Filename: $this->filename, Extension: $this->extension)";
    }
}