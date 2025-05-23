<?php
require_once __DIR__ . '/bootstrap.php';

class FEntityManager {
    private static $instance;

    private static $EntityManager; //crei la variabile dove verrà assegnato l' entity manager



    private function __construct() {
        self::$EntityManager = getEntityManager(); //richiami l'entity manager
        
    }

    public static function getInstance() {  
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getEntityManager() {
        return self::$EntityManager;
    }



    //questa funzione permette di trovare un entity usando la sua chiave primaria
    public static function RetriveObj($class, $id){
        try{
            $obj = self::$EntityManager->find($class,$id);
            return $obj;
        }catch(Exception $e){   
            echo "ERROR : ".$e->getMessage();
            return null;
    }
}


     //Recupera un oggetto usando un campo qualsiasi , se ce ne sono tanti prende il primo che trova
    public static function RetriveObjNotOnId($class,$field, $id){
        try{
            $obj = self::$EntityManager->getRepository($class)->findOneBy([$field => $id]); 
            return $obj;

        }catch(Exception $e){   
            echo "Error : ". $e->getMessage();
            return null;    
        }
    }


    //NELLE QUERY USIAMO LA e IN MODO TALE DA AVERE UN ALIAS PER RIFERIRICI A UN ENTITA'


    //Ritorna una lista di oggetti che hanno un determinato valore in un determinato campo
    public static function ListOfObj($entity,$field,$id){
        try{
            $query="SELECT e FROM " . $entity ." e WHERE e.". $field . "= :creatorId"; //abbiamo usato creatorId perchè è un parametro che evuta l'SQL injection , poi lo settiamo con un vero parametro
            $createquery = self::$EntityManager->createQuery($query); //creazione query
            $createquery-> setParameter("creatorID", $id); //cambiamo il parametro
            $result =$createquery->getResult(); //ritorniamo il risultato
            return($result);
    } catch (Exception $e){
        echo "Error : ". $e->getMessage();
        return [];  //ritorna un array vuoto in modo tale che si può fare un controllo isempty  
    }
}


//ritorna gli oggetti che hanno in un determinayo campo un valore null

public static function objListWithNull($entity,$field){
    try{
        $query="SELECT e FROM " . $entity . " e WHERE e." . $field . " IS NULL";
        $createquery = self::$EntityManager->createQuery($query); 
        //$createquery->setParameter(" IS NULL", null);  // non so se effettivamente serve
        $result = $createquery->getResult();
        if (count($result)>0) {

           return($result);

        } else {

            return array();

        }
        
    }
}


//ritorna oggetti che in tre determinati campi hanno tre determinati valori
public static function ListOfObjWithTwoAtt($entity,$field1,$id1,$field2,$id2,){
        try{
            $query="SELECT e FROM " . $entity ." e WHERE e.". $field1 . "= :Id1 AND e." . $field2 . "= :Id2 "; //abbiamo usato creatorId perchè è un parametro che evuta l'SQL injection , poi lo settiamo con un vero parametro
            $createquery = self::$EntityManager->createQuery($query); //creazione query
            $createquery-> setParameter("Id1", $id1); //cambiamo il parametro
            $createquery-> setParameter("Id2", $id2); 
            $result =$createquery->getResult(); //ritorniamo il risultato
            return($result);
    } catch (Exception $e){
        echo "Error : ". $e->getMessage();
        return [];  //ritorna una lista vuota in modo tale che si può fare un controllo isempty  
    }
}

//ritorna una lista di oggetti che in due determinati campi hanno due determinati valori
public static function ListOfObjWithThreeAtt($entity,$field1,$id1,$field2,$id2,$field3,$id3){
        try{
            $query="SELECT e FROM " . $entity ." e WHERE e.". $field1 . "= :Id1 AND e." . $field2 . "= :Id2 AND e." . $field3 . "= :Id3"; //abbiamo usato creatorId perchè è un parametro che evuta l'SQL injection , poi lo settiamo con un vero parametro
            $createquery = self::$EntityManager->createQuery($query); //creazione query
            $createquery-> setParameter("Id1", $id1); //cambiamo il parametro
            $createquery-> setParameter("Id2", $id2); 
            $createquery-> setParameter("Id3", $id3); 
            $result =$createquery->getResult(); //ritorniamo il risultato
            return($result);
    } catch (Exception $e){
        echo "Error : ". $e->getMessage();
        return [];  //ritorna una lista vuota in modo tale che si può fare un controllo isempty  
    }
}


// controlla se dato un determinato campo e un determinato valore per quel campo esiste almeno un oggetto che rispecchia questa caratteristica
public static function exsistAttribute($fieldid,$entity,$field,$id){
    try{
    $query = "SELECT u.". $fieldid ." FROM ". $entity ." u WHERE u.". $field . "= :id1";
    $createquery = self::$EntityManager->createQuery($query);
    $createquery->setParameter("id1", $id);
    $result= $createquery->getResult();
    if(count($result)> 0){
        return true;

    } else {
        return false;   
    }
} catch (Exception $e){
    echo "Error : ". $e->getMessage();
    return null;

}

}

//funzione che salva l'oggetto usando le funzioni dell entitymanager
public static function saveObj($obj){
    try{
        self::$EntityManager->getConnection()->beginTransaction();
        self::$EntityManager->persist($obj);
        self::$EntityManager->flush();
        self::$EntityManager->getConnection()->commit();
        return true;

} catch (Exception $e){ 
    self::$EntityManager->getConnection();
    echo "Error : ". $e->getMessage();
    return false;
}
}


//funzione che elimina l'oggetto usando le funzioni dell entitymanager
public static function deleteObj($obj){
    try{
        self::$EntityManager->getConnection()->beginTransaction();
        self::$EntityManager->remove( $obj );
        self::$EntityManager->flush();
        self::$EntityManager->getConnection()->commit();
        return true;

} catch (Exception $e){ 
    self::$EntityManager->getConnection();
    echo "Error : ". $e->getMessage();
    return false;
}
}



// Recupera tutti gli oggetti di una determinata entità
 public static function selectobj($entity){
 try{
    $query = "SELECT e FROM " . $entity ." e";
    $createquery = self::$EntityManager->createQuery( $query );
    $result = $createquery->getResult();
    return $result;
 } catch (Exception $e){
    echo "Error : ". $e->getMessage();
    return [];
 }

}
}


