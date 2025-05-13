<?php

class ERecensione {
    private $id;
    
    private $idPrenotazione;
    private $valutazione;
    private $commento;

    public function __construct(int $id, int $idUtente, int $idPrenotazione, int $valutazione, string $commento) {
        $this->id = $id;
        $this->idUtente = $idUtente;
        $this->idPrenotazione = $idPrenotazione;
        $this->valutazione = $valutazione;
        $this->commento = $commento;
    }

    public function getId(): int {
        return $this->id;
    }


    public function getIdPrenotazione(): int {
        return $this->idPrenotazione;
    }

    public function getValutazione(): int {
        return $this->valutazione;
    }

    public function getCommento(): string {
        return $this->commento;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIdPrenotazione(int $idPrenotazione): void {
        $this->idPrenotazione = $idPrenotazione;
    }

    public function setValutazione(int $valutazione): void {
        $this->valutazione = $valutazione;
    }

    public function setCommento(string $commento): void {
        $this->commento = $commento;
    }

    public function __toString(): string {
        return "ERecensione(ID: $this->id, ID Prenotazione: $this->idPrenotazione, Valutazione: $this->valutazione, Commento: $this->commento)";
    }
}