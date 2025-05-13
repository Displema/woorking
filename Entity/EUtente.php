<?php

class EUtente{
    private $id;
    private $nome;
    private $cognome;
    private $email;
    private $telefono;
    private $dataNascita;
    private $password;
    private $isAdmin;

    public function __construct(int $id, string $nome, string $cognome, string $email, string $telefono, DateTime $dataNascita, string $password, bool $isAdmin) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->dataNascita = $dataNascita;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->isAdmin = $isAdmin;
    }
    public function getId(): int {
        return $this->id;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getCognome(): string {
        return $this->cognome;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function getDataNascita(): DateTime {
        return $this->dataNascita;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getisAdmin(): bool {
        return $this->isAdmin;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setCognome(string $cognome): void {
        $this->cognome = $cognome;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTelefono(string $telefono): void {
        $this->telefono = $telefono;
    }

    public function setDataNascita(DateTime $dataNascita): void {
        $this->dataNascita = $dataNascita;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setisAdmin(bool $isAdmin): void {
        $this->isAdmin = $isAdmin;
    }

    public function __toString(): string {
        return "EUtente(ID: $this->id, Nome: $this->nome, Cognome: $this->cognome, Email: $this->email, Telefono: $this->telefono, Data di Nascita: " . $this->dataNascita->format('Y-m-d') . ", Password: $this->password, Admin: " . ($this->isAdmin ? 'Sì' : 'No'.")");
    }



}