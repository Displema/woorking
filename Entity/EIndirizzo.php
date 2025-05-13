<?php
class EIndirizzo {
    private $id;
    private $via;
    private $numeroCivico;
    private $citta;
    private $provincia;
    private $cap;

    public function __construct(int $id, string $via, string $numeroCivico, string $citta, string $provincia, string $cap) {
        $this->id = $id;
        $this->via = $via;
        $this->numeroCivico = $numeroCivico;
        $this->citta = $citta;
        $this->provincia = $provincia;
        $this->cap = $cap;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getVia(): string {
        return $this->via;
    }

    public function getNumeroCivico(): string {
        return $this->numeroCivico;
    }

    public function getCitta(): string {
        return $this->citta;
    }

    public function getProvincia(): string {
        return $this->provincia;
    }

    public function getCap(): string {
        return $this->cap;
    }

    public function setId(int $id): void {
        $this->id = $id;
  }

    public function setVia(string $via): void {
        $this->via = $via;
    }

    public function setNumeroCivico(string $numeroCivico): void {
        $this->numeroCivico = $numeroCivico;
    }

    public function setCitta(string $citta): void {
        $this->citta = $citta;
    }

    public function setProvincia(string $provincia): void {
        $this->provincia = $provincia;
    }

    public function setCap(string $cap): void {
        $this->cap = $cap;
    }

    public function __toString(): string {
        return "Indirizzo: {$this->via} {$this->numeroCivico}, {$this->citta}, {$this->provincia}, {$this->cap}";
    }
}

