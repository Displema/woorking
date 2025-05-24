<?php
 require_once 'C:/Users/39327/Desktop/UFFICI/Entity/EUfficio.php';
class FUfficio{

    public static function findbyIndirizzoDataFascia($indirizzo,$fascia,$date){
       $em= FEntityManager::getEntityManager(); 
       try{
        $query = "SELECT e FROM EUfficio e
                    JOIN e.intervalliDisponibilita idisp
                    JOIN e.idIndirizzo indirizzo
                    WHERE indirizzo.citta = :indirizzo
                    AND idisp.fascia = :fascia
                    AND idisp.dataInizio <= :data AND idisp.dataFine >= :data
";
        $createquery= $em->createQuery($query);
        $createquery->setParameter("fascia",$fascia);
        $createquery->setParameter("data",$date);
        $createquery->setParameter("indirizzo",$indirizzo);
        return $createquery->getResult();

        }catch(Exception $e){
            echo"Error:".$e->getMessage();
            return [];
        }
    }
}