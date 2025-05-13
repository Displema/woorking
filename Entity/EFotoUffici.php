<?php

class EFotoUffici{
    private $idFoto;
    private $idUfficio;

    public function __construct(int $idFoto, int $idUfficio) {
        $this->idFoto = $idFoto;
        $this->idUfficio = $idUfficio;
    }

    public function getIdFoto(): int{
        return $this->idFoto;
    }

    public function getIdUfficio(): int{
        return $this->idUfficio;
    }

    public function setIdFoto(int $idFoto): void{
        $this->idFoto = $idFoto;
    }

    public function setIdUfficio(int $idUfficio): void{
        $this->idUfficio = $idUfficio;
    }

    public function __toString(): string{
        return "EFotoUfficio(ID Foto: $this->idFoto, ID Ufficio: $this->idUfficio)";
    }
}