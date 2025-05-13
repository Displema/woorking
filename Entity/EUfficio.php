<?php
use Money\Money;
class EUfficio{
    private $id;
    private $idLocatore;
    private $idIndirizzo;
    private $titolo;
    private $prezzo;
    private $descrizione;
    private $numeroPostazioni;
    private $superficie;
    private $dataCaricamento;
    private $dataCancellazione;
    private $stato;  //usare tipo enum per i vari stati
    private $dataApprovazione;
    private $dataRifiuto;
    private $motivoRifiuto;

    public function __construct(int $id, int $idLocatore, int $idIndirizzo, string $titolo, Money $prezzo, string $descrizione, int $numeroPostazioni, float $superficie, DateTime $dataCaricamento, ?DateTime $dataCancellazione, string $stato, ?DateTime $dataApprovazione, ?DateTime $dataRifiuto, ?string $motivoRifiuto) {
        $this->id = $id;
        $this->idLocatore = $idLocatore;
        $this->idIndirizzo = $idIndirizzo;
        $this->titolo = $titolo;
        $this->prezzo = $prezzo;
        $this->descrizione = $descrizione;
        $this->numeroPostazioni = $numeroPostazioni;
        $this->superficie = $superficie;
        $this->dataCaricamento = $dataCaricamento;
        $this->dataCancellazione = $dataCancellazione;
        $this->stato = $stato;
        $this->dataApprovazione = $dataApprovazione;
        $this->dataRifiuto = $dataRifiuto;
        $this->motivoRifiuto = $motivoRifiuto;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getIdLocatore(): int {
        return $this->idLocatore;
    }

    public function getIdIndirizzo(): int {
        return $this->idIndirizzo;
    }

    public function getTitolo(): string {
        return $this->titolo;
    }

    public function getPrezzo(): Money {
        return $this->prezzo;
    }

    public function getDescrizione(): string {
        return $this->descrizione;
    }

    public function getNumeroPostazioni(): int {
        return $this->numeroPostazioni;
    }

    public function getSuperficie(): float {
        return $this->superficie;
    }

    public function getDataCaricamento(): DateTime {
        return $this->dataCaricamento;
    }

    public function getDataCancellazione(): ?DateTime {
        return $this->dataCancellazione;
    }

    public function getStato(): string {
        return $this->stato;
    }

    public function getDataApprovazione(): ?DateTime {
        return $this->dataApprovazione;
    }

    public function getDataRifiuto(): ?DateTime {
        return $this->dataRifiuto;
    }

    public function getMotivoRifiuto(): ?string {
        return $this->motivoRifiuto;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIdLocatore(int $idLocatore): void {
        $this->idLocatore = $idLocatore;
    }

    public function setIdIndirizzo(int $idIndirizzo): void {
        $this->idIndirizzo = $idIndirizzo;
    }

    public function setTitolo(string $titolo): void {
        $this->titolo = $titolo;
    }

    public function setPrezzo(Money $prezzo): void {
        $this->prezzo = $prezzo;
    }

    public function setDescrizione(string $descrizione): void {
        $this->descrizione = $descrizione;
    }

    public function setNumeroPostazioni(int $numeroPostazioni): void {
        $this->numeroPostazioni = $numeroPostazioni;
    }

    public function setSuperficie(float $superficie): void {
        $this->superficie = $superficie;
    }

    public function setDataCaricamento(DateTime $dataCaricamento): void {
        $this->dataCaricamento = $dataCaricamento;
    }

    public function setDataCancellazione(?DateTime $dataCancellazione): void {
        $this->dataCancellazione = $dataCancellazione;
    }

    public function setStato(string $stato): void {
        $this->stato = $stato;
    }

    public function setDataApprovazione(?DateTime $dataApprovazione): void {
        $this->dataApprovazione = $dataApprovazione;
    }

    public function setDataRifiuto(?DateTime $dataRifiuto): void {
        $this->dataRifiuto = $dataRifiuto;
    }

    public function setMotivoRifiuto(?string $motivoRifiuto): void {
        $this->motivoRifiuto = $motivoRifiuto;
    }

    public function __toString(): string {
        return "EUfficio(ID: $this->id, ID Locatore: $this->idLocatore, ID Indirizzo: $this->idIndirizzo, Titolo: $this->titolo, Prezzo: " . $this->prezzo->getAmount() . ", Descrizione: $this->descrizione, Numero Postazioni: $this->numeroPostazioni, Superficie: $this->superficie, Data Caricamento: " . $this->dataCaricamento->format('Y-m-d H:i:s') . ", Data Cancellazione: " . ($this->dataCancellazione ? $this->dataCancellazione->format('Y-m-d H:i:s') : 'null') . ", Stato: $this->stato, Data Approvazione: " . ($this->dataApprovazione ? $this->dataApprovazione->format('Y-m-d H:i:s') : 'null') . ", Data Rifiuto: " . ($this->dataRifiuto ? $this->dataRifiuto->format('Y-m-d H:i:s') : 'null') . ", Motivo Rifiuto: " . ($this->motivoRifiuto ? $this->motivoRifiuto : 'null)');
    }

}