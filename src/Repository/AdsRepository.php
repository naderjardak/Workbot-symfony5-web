<?php

namespace App\Repository;

use App\Entity\Ads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ads>
 *
 * @method Ads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ads[]    findAll()
 * @method Ads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ads::class);
    }

    public function save(Ads $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ads $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findonlyValid()
    {
        $em=$this->getEntityManager();

        $query = $em->createQuery('SELECT a FROM App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1');
        return $query->getResult();
    }
    public function findonlyValid1()
    {
        $em=$this->getEntityManager();

        $query = $em->createQuery('SELECT a FROM App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1');
        return $query->getResult();
    }
    public function findonlyValidd()
    {
        $em=$this->getEntityManager();

        $query = $em->createQuery('SELECT a FROM App\Entity\Ads a WHERE a.dateFin < CURRENT_DATE() or a.status=0');
        return $query->getResult();
    }

    public function findonlyValiddd()
    {
        $em=$this->getEntityManager();

        $query = $em->createQuery('SELECT a FROM App\Entity\Ads a WHERE a.dateFin = CURRENT_DATE() or a.status=0');
        return $query->getResult();
    }

    public function countadsV()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(a) from App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1'
        )->getSingleScalarResult();

    }

    public function countadsNV()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(a) from App\Entity\Ads a WHERE a.dateFin < CURRENT_DATE() and a.status=0'
        )->getSingleScalarResult();

    }

    public function countgold()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(a) from App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1 and a.type LIKE :type'
        )->setParameter('type','Gold')->getSingleScalarResult();
    }

    public function countgratuit()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(a) from App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1 and a.type LIKE :type'
        )->setParameter('type','Gratuit')->getSingleScalarResult();
    }
    public function countbronze()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(a) from App\Entity\Ads a WHERE a.dateFin > CURRENT_DATE() and a.status=1 and a.type LIKE :type'
        )->setParameter('type','Bronze')->getSingleScalarResult();
    }
//    /**
//     * @return Ads[] Returns an array of Ads objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ads
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
