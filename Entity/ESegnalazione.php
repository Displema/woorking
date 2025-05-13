<?php

class ESegnalazione{
    private $id;
    private $idPrenotazione;

    private $commento;

    public function __construct(int $id, int $idPrenotazione, string $commento) {
        $this->id = $id;
        $this->idPrenotazione = $idPrenotazione;
        $this->commento = $commento;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getIdPrenotazione(): int{
        return $this->idPrenotazione;
    }

    public function getCommento(): string{
        return $this->commento;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setIdPrenotazione(int $idPrenotazione): void{
        $this->idPrenotazione = $idPrenotazione;
    }

    public function setCommento(string $commento): void{
        $this->commento = $commento;
    }

    public function __toString(): string{
        return "ESegnalazione(ID: $this->id, ID Prenotazione: $this->idPrenotazione, Commento: $this->commento)";
    }

}