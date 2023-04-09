<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    public function save(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Utilisateur $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    /*
     * Requette pour les administrateurs
     */
public function findadmin(){
    return $this->createQueryBuilder('u')
    ->where('u.role= :val' )
    ->setParameter('val','Admin')
    ->getQuery()
    ->getResult();

}
    public function findorCreateFrom0auth(ResourceOwnerInterface $owner){
        $utilisateur = $this->createQueryBuilder('u')
            ->where('u.googleId= :val' )
            ->setParameters(
                [
                    'googleId' => $owner->getId()
                ]
            )
            ->getQuery()
            ->getResult();
        if($utilisateur){return $utilisateur;}
        $user = (new user())
            ->setGoogleId($owner->getId())
            ->setEmail($owner->getEmail());
        $em=$this->getEntityManager();
        $em->persist($utilisateur);
        $em->flush();
        return  $utilisateur;

    }


    /*
         * Requette pour les administrateurs
         */
    public function findclient(){
        return $this->createQueryBuilder('u')
            ->where('u.role= :val or u.role= :val1')
            ->setParameter('val','sociéte')
            ->setParameter('val1','candidat')
            ->getQuery()
            ->getResult();

    }
    public function countuser()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(u) from App\Entity\Utilisateur u'
        )->getSingleScalarResult();
    }
    public function countsociete()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(u) from App\Entity\Utilisateur u where u.role LIKE :role'
        )->setParameter('role','sociéte')->getSingleScalarResult();

    }
    public function countfbgoogle()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(u) from App\Entity\Utilisateur u where u.role LIKE :role'
        )->setParameter('role','ROLE_a')->getSingleScalarResult();

    }

    public function countAdmin()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(u) from App\Entity\Utilisateur u where u.role LIKE :role'
        )->setParameter('role','Admin')->getSingleScalarResult();

    }

    public function countcandidat()
    {
        return $this->getEntityManager()->createQuery(
            'SELECT count(u) from App\Entity\Utilisateur u where u.role LIKE :role'
        )->setParameter('role','candidat')->getSingleScalarResult();

    }

    public function search($term)
    {
        return $this->createQueryBuilder('Utilisateur')
            ->Where('utilisateur.nom LIKE :no')
            ->setParameter('no','%'.$term.'%')
            ->getQuery()
            ->execute();

    }

//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
