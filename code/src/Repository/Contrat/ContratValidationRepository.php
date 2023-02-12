<?php

namespace App\Repository\Contrat;

use App\Entity\Account\Departement;
use App\Entity\Account\User;
use App\Entity\Contrat\ContratValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContratValidation>
 *
 * @method ContratValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContratValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContratValidation[]    findAll()
 * @method ContratValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContratValidation::class);
    }

    public function save(ContratValidation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ContratValidation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ContratValidation[] Returns an array of ContratValidation objects
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

//    public function findOneBySomeField($value): ?ContratValidation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function countUserPendingValidation(User $user, $findHorsDelai = false)
    {
        $q = $this->createQueryBuilder('cv')
            ->select('count(cv.id)')
            ->where('cv.user = :user')
            ->andWhere('cv.updatedAt IS NULL')
            ->setParameter('user', $user);
        if ($findHorsDelai) {
            // delai column is a DateInterval, add it to createdAt, if less than now, it's a hors delai
            $q->andWhere('cv.createdAt + cv.delai < :now')
                ->setParameter('now', new \DateTime());
        }
        return $q->getQuery()
            ->getSingleScalarResult();
    }

    public function countUserByState(User $user, $state)
    {
        return $this->createQueryBuilder('cv')
            ->select('count(cv.id)')
            ->where('cv.user = :user')
            // Use join to find in related contrat entity if currentState is state
            ->join('cv.contrat', 'c')
            ->andWhere('c.currentState = :state')
            ->andWhere('cv.updatedAt IS NOT NULL')
            ->setParameter('user', $user)
            ->setParameter('state', $state)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countDepartementValidated(Departement $getDepartement)
    {
        return $this->createQueryBuilder('cv')
            ->select('count(cv.id)')
            // Use join to find in related contrat entity if departement is getDepartement
            ->join('cv.contrat', 'c')
            ->where('c.departementInitiateur = :departement')
            ->setParameter('departement', $getDepartement)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUserRequestsPendingForValidation(User $user)
    {
        return $this->createQueryBuilder('cv')
            ->select('count(cv.id)')
            // Use join to find in related contrat entity if ownedBy is user
            ->join('cv.contrat', 'c')
            ->where('c.ownedBy = :user')
            ->andWhere('cv.updatedAt IS NULL')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
