<?php

namespace App\Repository;

use App\Entity\Certification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\String_;

/**
 * @extends ServiceEntityRepository<Certification>
 *
 * @method Certification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Certification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Certification[]    findAll()
 * @method Certification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    public function save(Certification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Certification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function stat_count_certif(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select titreCours ,count(cb.id_user) nb from certification c join certif_badge cb on c.id=cb.id_certif GROUP BY titreCours';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }
    public function stat_count_user(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'select * from utilisateur where role="candidat"';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function stat_semaine_stat(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $time = (new \DateTime())->modify('-7 day') ;
        $t=$time->format('d_m_Y');


        $sql = 'SELECT * FROM `certification` WHERE dateAjout>:sysdate';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['sysdate' => $t]);
        return $resultSet->fetchAllAssociative();
    }

    public function cert_aff($id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM `certification` cert  WHERE id NOT IN (SELECT id_certif FROM `certif_badge` WHERE id_user=:id) and id_quiz>0 and 3<(select count(*) from quiz q join question_reponse qr on q.id=qr.id_quiz where q.id=cert.id_quiz )';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id'=>$id]);
        return $resultSet->fetchAllAssociative();
    }

    public function cert_all(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM `certification`';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();
        return $resultSet->fetchAllAssociative();
    }

    public function cert_search($requestString): array
    {
        /*
        return $this->createQueryBuilder('c')
            ->where('c.titrecours LIKE :req')
            ->setParameter('req','%'.$requestString.'%')
            ->getQuery()
            ->getResult();
        */

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM `certification` where (titreCours like :q ) ";
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['q'=>'%'.$requestString.'%']);
        return $resultSet->fetchAllAssociative();
        }




//    /**
//     * @return Certification[] Returns an array of Certification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Certification
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
