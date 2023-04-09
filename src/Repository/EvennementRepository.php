<?php

namespace App\Repository;

use App\Entity\Evennement;
use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Evennement>
 *
 * @method Evennement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evennement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evennement[]    findAll()
 * @method Evennement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvennementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evennement::class);
    }

    public function save(Evennement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evennement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function participnotin($iduser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          SELECT * FROM evennement e WHERE id NOT IN(select id_event from participation p WHERE p.id_event=e.id and p.id_userP=:iduser) and nbPlaces>0';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([ 'iduser'=>$iduser]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function participin($iduser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          SELECT * FROM evennement e WHERE id IN(select id_event from participation p WHERE nbPlaces>0 and p.id_event=e.id and p.id_userP=:iduser)';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([ 'iduser'=>$iduser]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    public function nbplaceupdate($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
         UPDATE evennement SET nbPlaces=(nbPlaces-1) where id=:id';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    public function annuleupdate($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
         UPDATE evennement SET nbPlaces=(nbPlaces+1) where id=:id';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function affichertoutev()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          SELECT * FROM evennement';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }


//    /**
//     * @return Evennement[] Returns an array of Evennement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evennement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
