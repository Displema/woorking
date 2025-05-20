<?php
require_once 'EUtente.php';

class ELocatore extends EUtente{
    private $partitaIva;

    public function __construct($id,$nome, $cognome, $email, $telefono, $dataNascita, $password, $isAdmin, $partitaIva) {
        parent::__construct($id);
        $this->partitaIva = $partitaIva;
    }

    public function getPartitaIva(): string {
        return $this->partitaIva;
    }

    public function setPartitaIva(string $partitaIva): void {
        $this->partitaIva = $partitaIva;
    }

    public function __toString(): string {
        return parent::__toString() . ", Partita IVA: $this->partitaIva";
    }
}