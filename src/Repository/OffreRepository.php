<?php

namespace App\Repository;

use App\Entity\Offre;
use ContainerIDxsAc9\getDoctrineMigrations_UpToDateCommandService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @extends ServiceEntityRepository<Offre>
 *
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function save(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Offre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findNBoffresAdmin()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(o) from App\Entity\Offre o'
        )->getSingleScalarResult();
    }


    public function findByIdAndCountCand($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(c) from App\Entity\Candidature c join c.idOffre o where o.id=:id'
        )->setParameter('id',$id)->getSingleScalarResult();
    }
//    public function findAllCandidates($id)
//    {
//        return $this->getEntityManager()->createQuery(
//            'SELECT c from App\Entity\Candidature c join c.idOffre o join c.idcondidat u where c.idOffre=:id'
//        )
//            ->setParameter('id',$id)
//           ->getArrayResult();
//    }

    public function findAllCandidates3($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
           SELECT o.id ,u.id,c.id,u.email email, u.nom nom, u.adresse address,u.photo photo,
                  c.dateAjout dateajout,c.Experience experience,c.noteTest notetest,c.NiveauAnglais anglais,c.NiveauFrancais francais,o.dateexpiration dateexpiration,o.titre titre,c.domaine domaine,c.lettreMotivation,c.Cv cv,c.diplome diplome,c.statut statut
                     from `candidature` c join `offre` o on c.id_offre=o.id 
                        JOIN `utilisateur` u ON u.id=c.idcondidat where o.id= :id
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['id' => $id]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
    public function findOffres()
    {
        return $this->createQueryBuilder('o')
            ->Where('o.typeoffre = Stage')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOffresBytitre($titre,$id)
    {
        return $this->createQueryBuilder('o')
            ->Where('o.titre LIKE :titre')
            ->andWhere('o.idSoc = :idSoc')
            ->setParameter('titre','%'.$titre.'%')
            ->setParameter('idSoc', $id )
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOffreByWeek()
    {
        return $this->createQueryBuilder('o')
            ->Where('TIMESTAMPDIFF(WEEK,CURRENT_DATE(),o.dateajout) =0')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findOffreLastWeek()
    {
        return $this->createQueryBuilder('o')
            ->Where('TIMESTAMPDIFF(WEEK,CURRENT_DATE(),o.dateajout) =-1')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOffreByMonth()
    {
        return $this->createQueryBuilder('o')
            ->Where('TIMESTAMPDIFF(MONTH,CURRENT_DATE(),o.dateajout) =0')
            ->getQuery()
            ->getResult();
    }
    public function findOffreOfLastMonth()
    {
        return $this->createQueryBuilder('o')
            ->Where('TIMESTAMPDIFF(MONTH,o.dateajout,CURRENT_DATE()) =1')
            ->getQuery()
            ->getResult();
    }
    public function findOffreByYear()
    {
        return $this->createQueryBuilder('o')
            ->Where('TIMESTAMPDIFF(YEAR,CURRENT_DATE(),o.dateajout) =0')
            ->getQuery()
            ->getResult();
    }
    public function findOffreByMonthDiff($month,$typeO)
    {
        return $this->createQueryBuilder('o')
            ->Where('month(o.dateajout) =?1')->andWhere('o.typeoffre = :type')
            ->setParameter('1',$month)
            ->setParameter('type',$typeO)
            ->getQuery()
            ->getResult();
    }
    public function findOffreByDate()
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.dateajout','DESC')
            ->getQuery()
            ->getResult();
    }



//    public function findAllCandidates2($id)
//    {
//        return $this->getEntityManager()->createQueryBuilder()
//            ->from(Candidature::class,'candidature')
//            ->select('candidature.dateajout,
//                            offre.titre,
//                            offre.id,
//                            user.nom,
//                            user.email')
//            ->join('offre','WITH','candidature.idOffre=offre.id')
//            ->join('user','WITH','candidature.idcondidat=user.id')
//            ->where("offre.id =:id")
//            ->setParameter('id',$id)
//            ->getQuery()
//            ->getResult();
//
//    }

//    public function findOneBySomeField($value): ?Offre
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
