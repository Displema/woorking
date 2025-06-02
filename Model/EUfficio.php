<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;
use Model\Enum\StatoUfficioEnum;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use TechnicalServiceLayer\Repository\EUfficioRepository;

#[ORM\Entity(repositoryClass: EUfficioRepository::class)]
 #[ORM\Table(name: "Uffici")]
class EUfficio
{

     #[ORM\Id]
     #[ORM\Column(type: "uuid", unique: true)]
     #[ORM\GeneratedValue(strategy: "CUSTOM")]
     #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface $id;
    
      #[ORM\ManyToOne(targetEntity:ELocatore::class,cascade:["persist"])]
      //#[ORM\JoinColumn(name:"idLocatore", referencedColumnName:"id")]
    private ELocatore $locatore;

    #[ORM\OneToMany(targetEntity:EFoto::class, mappedBy:"ufficio", cascade:["persist", "remove"])]
    private Collection $foto;
    
     #[ORM\ManyToOne(targetEntity:EIndirizzo::class)]
    private EIndirizzo $indirizzo;

     #[ORM\Column]
    private string $titolo;

    #[ORM\OneToMany(targetEntity:EIntervalloDisponibilita::class, mappedBy:"ufficio", cascade:["persist", "remove"])]
    private Collection $intervalliDisponibilita;

     #[ORM\Column(type:"integer")]
    private int $prezzo;
    
    #[ORM\Column(type:"string")]
    private $descrizione;
    
     #[ORM\Column(name: 'numero_postazioni', type: "integer")]
    private $numeroPostazioni;
    
      #[ORM\Column(type:"float")]
    private $superficie;
    
    #[ORM\Column(name: 'data_caricamento', type: "datetime")]
    private $dataCaricamento;
    
     #[ORM\Column(name: 'data_cancellazione', type: "datetime", nullable: true)]
    private $dataCancellazione;
    
     #[ORM\Column(type:"string", enumType:Enum\StatoUfficioEnum::class)]
    private StatoUfficioEnum $stato;
    
     #[ORM\Column(name: "data_approvazione", type: "datetime", nullable: true)]
    private ?DateTime $dataApprovazione;
    
     #[ORM\Column(name: 'data_rifiuto', type: "datetime", nullable: true)]
    private ?DateTime $dataRifiuto;
    
     #[ORM\Column(name: 'motivo_rifiuto', type: "string", nullable: true)]
    private ?string $motivoRifiuto;

    #[ORM\OneToMany(targetEntity:ESegnalazione::class, mappedBy:"ufficio", cascade:["persist", "remove"])]
     private Collection $segnalazioni;

    #[ORM\OneToMany(targetEntity:EServiziAggiuntivi::class, mappedBy:"ufficio", cascade:["persist", "remove"])]
    private Collection $serviziAggiuntivi;

    public function __construct()
    {
        //$this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLocatore(): ELocatore
    {
        return $this->locatore;
    }

    public function getIndirizzo(): EIndirizzo
    {
        return $this->indirizzo;
    }
    public function getFoto(): Collection
    {
        return $this->foto;
    }

    public function getTitolo(): string
    {
        return $this->titolo;
    }

    public function getPrezzo(): int
    {
        return $this->prezzo;
    }

    public function getDescrizione(): string
    {
        return $this->descrizione;
    }

    public function getNumeroPostazioni(): int
    {
        return $this->numeroPostazioni;
    }

    public function getSuperficie(): float
    {
        return $this->superficie;
    }

    public function getsegnalazione(): Collection{
        return $this->segnalazioni;
    }

    public function getDataCaricamento(): DateTime
    {
        return $this->dataCaricamento;
    }

    public function getDataCancellazione(): ?DateTime
    {
        return $this->dataCancellazione;
    }

    public function getStato(): StatoUfficioEnum
    {
        return $this->stato;
    }

    public function getDataApprovazione(): ?DateTime
    {
        return $this->dataApprovazione;
    }

    public function getDataRifiuto(): ?DateTime
    {
        return $this->dataRifiuto;
    }

    public function getMotivoRifiuto(): ?string
    {
        return $this->motivoRifiuto;
    }

    public function getServiziAggiuntivi(): Collection
    {
        return $this->serviziAggiuntivi;
    }

    public function setLocatore(ELocatore $locatore): EUfficio
    {
        $this->locatore = $locatore;
        return $this;
    }



    public function setIndirizzo(EIndirizzo $indirizzo): EUfficio
    {
        $this->indirizzo = $indirizzo;
        return $this;
    }

    public function setTitolo(string $titolo): EUfficio
    {
        $this->titolo = $titolo;
        return $this;
    }

    public function setPrezzo(int $prezzo): EUfficio
    {
        $this->prezzo = $prezzo;
        return $this;
    }

    public function setDescrizione(string $descrizione): EUfficio
    {
        $this->descrizione = $descrizione;
        return $this;
    }

    public function setNumeroPostazioni(int $numeroPostazioni): EUfficio
    {
        $this->numeroPostazioni = $numeroPostazioni;
        return $this;
    }

    public function setSuperficie(float $superficie): EUfficio
    {
        $this->superficie = $superficie;
        return $this;
    }

    public function setDataCaricamento(DateTime $dataCaricamento): EUfficio
    {
        $this->dataCaricamento = $dataCaricamento;
        return $this;
    }

    public function setDataCancellazione(?DateTime $dataCancellazione): EUFFicio
    {
        $this->dataCancellazione = $dataCancellazione;
        return $this;
    }

    public function setStato(StatoUfficioEnum $stato): EUFFicio
    {
        $this->stato = $stato;
        return $this;
    }

    public function setDataApprovazione(?DateTime $dataApprovazione): EUFFicio
    {
        $this->dataApprovazione = $dataApprovazione;
        return $this;
    }

    public function setDataRifiuto(?DateTime $dataRifiuto): EUFFicio
    {
        $this->dataRifiuto = $dataRifiuto;
        return $this;
    }

    public function setMotivoRifiuto(?string $motivoRifiuto): EUfficio
    {
        $this->motivoRifiuto = $motivoRifiuto;
        return $this;
    }
    public function addFoto(EFoto $foto) : EUFFicio
    {
        if (!$this->foto->contains($foto)) {
            $this->foto[] = $foto;
            $foto->setUfficio($this);
        }
        return $this;
    }
    public function removeFoto(EFoto $foto) : EUfficio
    {
        if ($this->foto->removeElement($foto)) {
            if ($foto->getUfficio() === $this) {
                $foto->setUfficio(null);
            }
        }
        return $this;
    }


    public function addServizioAggiuntivo(EServiziAggiuntivi $servizio): EUFFicio
    {
        if (!$this->serviziAggiuntivi->contains($servizio)) {
            $this->serviziAggiuntivi->add($servizio);
            $servizio->setUfficio($this);
        }
        return $this;
    }

    public function removeServizioAggiuntivo(EServiziAggiuntivi $servizio): EUFFicio
    {
        if ($this->serviziAggiuntivi->removeElement($servizio)) {
            if ($servizio->getUfficio() === $this) {
                $servizio->setUfficio(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return "EUfficio(ID:". $this->id.", ID Locatore: $this->locatore, Indirizzo: $this->indirizzo, Titolo: $this->titolo, Prezzo: " . $this->prezzo . ", Descrizione: $this->descrizione, Numero Postazioni: $this->numeroPostazioni, Superficie: $this->superficie, Data Caricamento: " . $this->dataCaricamento->format('Y-m-d H:i:s') . ", Data Cancellazione: " . ($this->dataCancellazione ? $this->dataCancellazione->format('Y-m-d H:i:s') : 'null') . ", Stato:". $this->stato->value." , Data Approvazione: " . ($this->dataApprovazione ? $this->dataApprovazione->format('Y-m-d H:i:s') : 'null') . ", Data Rifiuto: " . ($this->dataRifiuto ? $this->dataRifiuto->format('Y-m-d H:i:s') : 'null') . ", Motivo Rifiuto: " . ($this->motivoRifiuto ? $this->motivoRifiuto : 'null)');
    }
}
