<?php

namespace App\Repository\Contrat;

use App\Entity\Contrat\PeriodicitePaiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PeriodicitePaiement>
 *
 * @method PeriodicitePaiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodicitePaiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodicitePaiement[]    findAll()
 * @method PeriodicitePaiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicitePaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeriodicitePaiement::class);
    }

    public function save(PeriodicitePaiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PeriodicitePaiement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PeriodicitePaiement[] Returns an array of PeriodicitePaiement objects
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

//    public function findOneBySomeField($value): ?PeriodicitePaiement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
