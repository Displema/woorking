<?php
use Money\Money;

class EPagamento{
    private $id;
    private $idPrenotazione;
    private $importo;

    public function __construct(int $id, int $idPrenotazione, Money $importo) {
        $this->id = $id;
        $this->idPrenotazione = $idPrenotazione;
        $this->importo = $importo;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getIdPrenotazione(): int{
        return $this->idPrenotazione;
    }

    public function getImporto(): Money{
        return $this->importo;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setIdPrenotazione(int $idPrenotazione): void{
        $this->idPrenotazione = $idPrenotazione;
    }

    public function setImporto(Money $importo): void{
        $this->importo = $importo;
    }

    public function __toString(): string{
        return "EPagamento(ID: $this->id, ID Prenotazione: $this->idPrenotazione, Importo: " . $this->importo->getAmount() .")";
    }


}