<?php

class EPrenotazione{
    private $id;
    private $idUfficio;
    private $idUtente;
    private $fascia;
    private $data;

    public function __construct(int $id, int $idUfficio, int $idUtente, string $fascia, DateTime $data) {
        $this->id = $id;
        $this->idUfficio = $idUfficio;
        $this->idUtente = $idUtente;
        $this->fascia = $fascia;
        $this->data = $data;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getIdUfficio(): int{
        return $this->idUfficio;
    }

    public function getIdUtente(): int{
        return $this->idUtente;
    }

    public function getFascia(): string{
        return $this->fascia;
    }

    public function getData(): DateTime{
        return $this->data;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setIdUfficio(int $idUfficio): void{
        $this->idUfficio = $idUfficio;
    }

    public function setIdUtente(int $idUtente): void{
        $this->idUtente = $idUtente;
    }

    public function setFascia(string $fascia): void{
        $this->fascia = $fascia;
    }

    public function setData(DateTime $data): void{
        $this->data = $data;
    }

    public function __toString(): string{
        return "ID: $this->id, ID Ufficio: $this->idUfficio, ID Utente: $this->idUtente, Fascia: $this->fascia, Data: " . $this->data->format('Y-m-d H:i:s');
    }
}