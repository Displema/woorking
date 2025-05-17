<?php
use Money\Money;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use Enum\StatoUfficio;
use ELocatore;
use EIndirizzo;

 #[ORM\Entity]
 #[ORM\Table(name: "Ufficio")]
class EUfficio{
    
     #[ORM\Id]
     #[ORM\Column(type:"guid",unique:true)] 
    private UuidInterface $id;
    
      #[ORM\ManyToOne(targetEntity:ELocatore::class)]
      #[ORM\JoinColumn(name:"idLocatore", referencedColumnName:"id")]
    private ELocatore $idLocatore;


    #[ORM\OneToMany(targetEntity:EFoto::class,mappedBy:"Ufficio",cascade:["persist", "remove"])]
    private Collection $foto;
    
     #[ORM\ManyToOne(targetEntity:EIndirizzo::class)]
     #[ORM\JoinColumn(name:"IdIndirizzo",referencedColumnName:"id")]
    private EIndirizzo $idIndirizzo;
    
      #[ORM\Column(type:"string")]
    private $titolo;

    
     #[ORM\Column(type:"integer")]       //NEL COSTRUTTORE ABBIAMO MONEY MA DOCTRINE NON LO GESTISCE E QUI HO MESSO INT
    private $prezzo;
    
    #[ORM\Column(type:"string")]
    private $descrizione;
    
     #[ORM\Column(type:"integer")]
    private $numeroPostazioni;
    
      #[ORM\Column(type:"float")]
    private $superficie;
    
    #[ORM\Column(type:"datetime")]
    private $dataCaricamento;
    
     #[ORM\Column(type:"datetime",nullable:true)]
    private $dataCancellazione;
    
     #[ORM\Column(type:"string", enumType:Enum\StatoUfficio::class)]
    private StatoUfficio $stato;  //usare tipo enum per i vari stati
    
     #[ORM\Column(type:"datetime",nullable:true)] 
    private $dataApprovazione;
    
     #[ORM\Column(type:"datetime",nullable:true)]
    private $dataRifiuto;
    
     #[ORM\Column(type:"string",nullable:true)]
    private $motivoRifiuto;

    #[ORM\OneToMany(targetEntity:EServiziAggiuntivi::class,mappedBy:"Ufficio",cascade:["persist", "remove"])]
    private Collection $serviziAggiuntivi;

    public function __construct( ELocatore $idLocatore, EIndirizzo $idIndirizzo,  int $prezzo) {
        $this->id = Uuid::uuid4();
        $this->idLocatore = $idLocatore;
        $this->idIndirizzo = $idIndirizzo;
        $this->prezzo = $prezzo;

        $this->foto = new ArrayCollection();
        $this->serviziAggiuntivi = new ArrayCollection();
    }

    public function getId(): UuidInterface {
        return $this->id;
    }

    public function getIdLocatore(): ELocatore {
        return $this->idLocatore;
    }

    public function getIdIndirizzo(): EIndirizzo {
        return $this->idIndirizzo;
    }
    public function getfoto(): Collection{
        return $this->foto;
    }

    public function getTitolo(): string {
        return $this->titolo;
    }

    public function getPrezzo(): int {
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

    public function getStato(): StatoUfficio {
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

    public function getServiziAggiuntivi(): Collection {
        return $this->serviziAggiuntivi;
    }

    public function setId(UuidInterface $id): void {
        $this->id = $id;
    }

    public function setIdLocatore(ELocatore $idLocatore): void {
        $this->idLocatore = $idLocatore;
    }

    public function setIdIndirizzo(EIndirizzo $idIndirizzo): void {
        $this->idIndirizzo = $idIndirizzo;
    }

    public function setTitolo(string $titolo): void {
        $this->titolo = $titolo;
    }

    public function setPrezzo(int $prezzo): void {
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

    public function setStato(StatoUfficio $stato): void {
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
    public function addFoto(EFoto $foto) : void {
        if (!$this->foto->contains($foto)) {
        $this->foto[] = $foto;
        $foto->setUfficio($this); 
    }
    
    }
    public function removeFoto(EFoto $foto) : void {
         if ($this->foto->removeElement($foto)) {
        if ($foto->getUfficio() === $this) {
            $foto->setUfficio(null);
        }
    }
}


public function addServizioAggiuntivo(EServiziAggiuntivi $servizio): void {
    if (!$this->serviziAggiuntivi->contains($servizio)) {
        $this->serviziAggiuntivi->add($servizio);
        $servizio->setUfficio($this);
    }
}

public function removeServizioAggiuntivo(EServiziAggiuntivi $servizio): void {
    if ($this->serviziAggiuntivi->removeElement($servizio)) {
        if ($servizio->getUfficio() === $this) {
            $servizio->setUfficio(null);
        }
    }
}

    public function __toString(): string {
        return "EUfficio(ID:". $this->id->__tostring() .", ID Locatore: $this->idLocatore, ID Indirizzo: $this->idIndirizzo, Titolo: $this->titolo, Prezzo: " . $this->prezzo . ", Descrizione: $this->descrizione, Numero Postazioni: $this->numeroPostazioni, Superficie: $this->superficie, Data Caricamento: " . $this->dataCaricamento->format('Y-m-d H:i:s') . ", Data Cancellazione: " . ($this->dataCancellazione ? $this->dataCancellazione->format('Y-m-d H:i:s') : 'null') . ", Stato:". $this->stato->value." , Data Approvazione: " . ($this->dataApprovazione ? $this->dataApprovazione->format('Y-m-d H:i:s') : 'null') . ", Data Rifiuto: " . ($this->dataRifiuto ? $this->dataRifiuto->format('Y-m-d H:i:s') : 'null') . ", Motivo Rifiuto: " . ($this->motivoRifiuto ? $this->motivoRifiuto : 'null)');
    }

}