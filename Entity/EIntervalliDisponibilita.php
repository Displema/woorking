<?php

class EIntervalliDisponibilita{
    private $idUfficio;
    private $dataInizio;
    private $dataFine;

    public function __construct(int $idUfficio, DateTime $dataInizio, DateTime $dataFine) {
        $this->idUfficio = $idUfficio;
        $this->dataInizio = $dataInizio;
        $this->dataFine = $dataFine;
    }

    public function getIdUfficio(): int {
        return $this->idUfficio;
    }

    public function getDataInizio(): DateTime {
        return $this->dataInizio;
    }

    public function getDataFine(): DateTime {
        return $this->dataFine;
    }

    public function setIdUfficio(int $idUfficio): void {
        $this->idUfficio = $idUfficio;
    }

    public function setDataInizio(DateTime $dataInizio): void {
        $this->dataInizio = $dataInizio;
    }

    public function setDataFine(DateTime $dataFine): void {
        $this->dataFine = $dataFine;
    }

    public function __toString(): string {
        return "Intervallo Disponibilità (ID Ufficio: $this->idUfficio, Data Inizio: " . $this->dataInizio->format('Y-m-d H:i:s') . ", Data Fine: " . $this->dataFine->format('Y-m-d H:i:s') . ")";
    }
}