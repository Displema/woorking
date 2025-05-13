<?php

class EServiziAggiuntivi{
    private $idUfficio;
    private $nomeServizio; //che tipo usiamo?

    public function __construct(int $idUfficio, string $nomeServizio){ 
        $this->idUfficio = $idUfficio;
        $this->nomeServizio = $nomeServizio;
    }

   public function getIdUfficio(): int{
    return $this->idUfficio;
   }

   public function getNomeServizio(): string{
    return $this->nomeServizio;
   }
   
   public function setIdUfficio(int $idUfficio):void{
    $this->idUfficio = $idUfficio;
   }

   public function setNomeServizio(string $nomeServizio):void{
    $this->nomeServizio = $nomeServizio;
   }

   public function __tostring() :string{
    return "EServiziAggiuntivi(ID: $this->idUfficio, Nome Servizio: $this->nomeServizio)";
   }
}