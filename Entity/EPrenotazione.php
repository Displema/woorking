<?php
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use EUfficio;
use Eutente;
use Enum\FasciaPrenotazione;
use DateTime;
/**
 * @ORM\Entity
 * @ORM\Table(name="Prenotazione")
 */
class EPrenotazione{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid",unique=true)
     */
     private UuidInterface $id;
    /**
     * @ORM\ManyToOne(targetEntity=EUfficio::class)
     * @ORM\JoinColumn(name="idUfficio",referencedColumnName="id")
     */
    private EUfficio $idUfficio;
    /**
     * @ORM\ManyToOne(targetEntity=EUtente::class)
     * @ORM\JoinColumn(name="idUtente",referencedColumnName="id")
     */
    private EUtente $idUtente;
    /**
     * @ORM\Column(type="string",enumType=Enum\FasciaPrenotazione::class)
     */
    private FasciaPrenotazione $fascia;
    /**
     * @ORM\Column(type="datetime")
     */
    private $data;

    public function __construct(EUfficio $idUfficio, EUtente $idUtente) {
        $this->id = Uuid::uuid4();
        $this->idUfficio = $idUfficio;
        $this->idUtente = $idUtente;

    }

    public function getId(): UuidInterface{
        return $this->id;
    }

    public function getIdUfficio(): EUfficio{
        return $this->idUfficio;
    }

    public function getIdUtente(): EUtente{
        return $this->idUtente;
    }

    public function getFascia(): FasciaPrenotazione {
        return $this->fascia;
    }

    public function getData(): DateTime{
        return $this->data;
    }

    public function setId(UuidInterface $id): void{
        $this->id = $id;
    }

    public function setIdUfficio(EUfficio $idUfficio): void{
        $this->idUfficio = $idUfficio;
    }

    public function setIdUtente(EUtente $idUtente): void{
        $this->idUtente = $idUtente;
    }

    public function setFascia(FasciaPrenotazione $fascia): void{
        $this->fascia = $fascia;
    }

    public function setData(DateTime $data): void{
        $this->data = $data;
    }

    public function __toString(): string{
        return "EPrenotazione(ID:". $this->id->__toString() .", ID Ufficio:" .$this->idUfficio->__toString()  . ", ID Utente:". $this->idUtente->__toString().", Fascia:". $this->fascia->value .", Data: " . $this->data->format('Y-m-d H:i:s').")";
    }
}