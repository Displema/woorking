<?php
use Money\Money;


class ERimborso{
    private $id;
    private $idSegnalazione;
    private $valore;

    public function __construct(int $id, int $idSegnalazione,  Money $valore) {
        $this->id = $id;
        $this->idSegnalazione = $idSegnalazione;
        $this->valore = $valore;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getIdSegnalazione(): int{
        return $this->idSegnalazione;
    }

    public function getValore(): Money{
        return $this->valore;
    }

    public function setId(int $id): void{
        $this->id = $id;
    }

    public function setIdSegnalazione(int $idSegnalazione): void{
        $this->idSegnalazione = $idSegnalazione;
    }

    public function setValore(Money $valore): void{
        $this->valore = $valore;
    }

    public function __toString(): string{
        return "Rimborso(ID: $this->id, ID Segnalazione: $this->idSegnalazione, Valore: " . $this->valore->getAmount() . ")";
    }

}