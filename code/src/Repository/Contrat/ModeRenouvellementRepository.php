<?php

namespace App\Repository\Contrat;

use App\Entity\Contrat\ModeRenouvellement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModeRenouvellement>
 *
 * @method ModeRenouvellement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeRenouvellement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeRenouvellement[]    findAll()
 * @method ModeRenouvellement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeRenouvellementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeRenouvellement::class);
    }

    public function save(ModeRenouvellement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ModeRenouvellement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ModeRenouvellement[] Returns an array of ModeRenouvellement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ModeRenouvellement
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
