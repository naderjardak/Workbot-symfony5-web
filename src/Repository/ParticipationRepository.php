<?php

namespace App\Repository;

use App\Entity\Participation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Participation>
 *
 * @method Participation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participation[]    findAll()
 * @method Participation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participation::class);
    }

    public function save(Participation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Participation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function voirp($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
           SELECT u.prenom,u.nom,u.email FROM utilisateur u JOIN participation p ON u.id=p.id_userP WHERE p.id_event=:id
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    public function particip($id,$iduser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          INSERT INTO participation(id_event,id_userP) VALUES(:id,:iduser)';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id , 'iduser'=>$iduser]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    public function deleteparticipation($id,$iduser)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
          DELETE FROM participation WHERE id_userP=:iduser and id_event=:id';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id , 'iduser'=>$iduser]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return Participation[] Returns an array of Participation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Participation
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
