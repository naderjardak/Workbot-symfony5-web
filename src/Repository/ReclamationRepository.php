<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reclamation>
 *
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function save(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reclamation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


//    /**
//     * @return Reclamation[] Returns an array of Reclamation objects
//     */
    public function findByReclamationByReference($reference)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id LIKE :reference')
            ->setParameter('reference', '%'.$reference.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findReclamationsByCategorie($cat)
    {
        return $this->createQueryBuilder('r')
            ->join('r.idCategorie' , 'c')
            ->addSelect('c')
            ->where('c.nomcategorie=:cat')
            ->setParameter('cat',$cat)
            ->getQuery()
            ->getResult()
            ;
    }
    public function countByDate(){
        // $query = $this->createQueryBuilder('a')
        //     ->select('SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count')
        //     ->groupBy('dateAnnonces')
        // ;
        // return $query->getQuery()->getResult();
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.dateajout, 1, 10) as dateReclamations, COUNT(a) as count FROM App\Entity\Reclamation a GROUP BY dateReclamations
        ");
        return $query->getResult();
    }
//    public function findOneBySomeField($value): ?Reclamation
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
