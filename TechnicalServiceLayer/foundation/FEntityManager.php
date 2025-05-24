<?php
class FEntityManager
{
    private static $instance;

    private static $EntityManager; //crei la variabile dove verrà assegnato l' entity manager



    private function __construct()
    {
        self::$EntityManager = getEntityManager(); //richiami l'entity manager
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getEntityManager()
    {
        return self::$EntityManager;
    }



    //questa funzione permette di trovare un entity usando la sua chiave primaria
    public static function retriveObj($class, $id)
    {
        try {
            return self::$EntityManager->find($class, $id);
        } catch (Exception $e) {
            echo "ERROR : ".$e->getMessage();
            return null;
        }
    }


     //Recupera un oggetto usando un campo qualsiasi , se ce ne sono tanti prende il primo che trova
    public static function retriveObjNotOnId($class, $field, $id)
    {
        try {
            return self::$EntityManager->getRepository($class)->findOneBy([$field => $id]);
        } catch (Exception $e) {
            echo "Error : ". $e->getMessage();
            return null;
        }
    }


    //NELLE QUERY USIAMO LA e IN MODO TALE DA AVERE UN ALIAS PER RIFERIRICI A UN ENTITA'
    //Ritorna una lista di oggetti che hanno un determinato valore in un determinato campo
    public static function listOfObj($entity, $field, $id)
    {
        try {
            $qb = self::$EntityManager->createQueryBuilder();

            $qb->select('e')
                ->from($entity, 'e')
                ->where("e.$field = :value")
                ->setParameter('value', $id);

            return $qb->getQuery()->getResult();
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return []; // ritorna array vuoto per controllo con empty()
        }
    }



    //ritorna gli oggetti che hanno in un determinayo campo un valore null
    public static function objListWithNull(string $entity, string $field): array
    {
        try {
            $qb = self::$EntityManager->createQueryBuilder();

            $qb->select('e')
                ->from($entity, 'e')
                ->where("e.$field IS NULL");

            $result = $qb->getQuery()->getResult();

            return $result ?? [];

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }

    public static function listOfObjByFields(string $entity, array $criteria): array
    {
        try {
            $qb = self::$EntityManager->createQueryBuilder();
            $qb->select('e')
                ->from($entity, 'e');

            $i = 1;
            foreach ($criteria as $field => $value) {
                $paramName = 'param' . $i;
                $qb->andWhere("e.$field = :$paramName")
                    ->setParameter($paramName, $value);
                $i++;
            }

            return $qb->getQuery()->getResult();

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }


// controlla se dato un determinato campo e un determinato valore per quel campo esiste almeno un oggetto che rispecchia questa caratteristica
    public static function existAttribute(string $fieldId, string $entity, string $field, $id): bool
    {
        try {
            $qb = self::$EntityManager->createQueryBuilder();

            $qb->select("u.$fieldId")
                ->from($entity, 'u')
                ->where("u.$field = :id")
                ->setParameter('id', $id)
                ->setMaxResults(1); // basta 1 risultato per dire che esiste

            $result = $qb->getQuery()->getOneOrNullResult();

            return $result !== null;

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

//funzione che salva l'oggetto usando le funzioni dell entitymanager
    public static function saveObj($obj): bool
    {
        try {
            self::$EntityManager->getConnection()->beginTransaction();
            self::$EntityManager->persist($obj);
            self::$EntityManager->flush();
            self::$EntityManager->getConnection()->commit();
            return true;
        } catch (\Doctrine\DBAL\Exception $e) {
            self::$EntityManager->getConnection()->rollBack();
            echo "Error : ". $e->getMessage();
            return false;
        }
    }


//funzione che elimina l'oggetto usando le funzioni dell entitymanager
    public static function deleteObj($obj): bool
    {
        try {
            self::$EntityManager->getConnection()->beginTransaction();
            self::$EntityManager->remove($obj);
            self::$EntityManager->flush();
            self::$EntityManager->getConnection()->commit();
            return true;
        } catch (\Doctrine\DBAL\Exception $e) {
            // in caso di errore annulla tutto quello che si stava facendo in precedenza
            self::$EntityManager->getConnection()->rollBack();
            echo "Error : ". $e->getMessage();
            return false;
        } catch (\Doctrine\ORM\Exception\ORMException $e) {
            self::$EntityManager->getConnection()->rollBack();
            echo "Error : ". $e->getMessage();
            return false;
        }
    }



// Recupera tutti gli oggetti di una determinata entità
    public static function selectObj(string $entity): array
    {
        try {
            $qb = self::$EntityManager->createQueryBuilder();

            $qb->select('e')
                ->from($entity, 'e');

            return $qb->getQuery()->getResult();

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return [];
        }
    }
}
