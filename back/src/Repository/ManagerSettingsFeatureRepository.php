<?php

namespace App\Repository;

use App\Entity\ManagerSettingsFeature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ManagerSettingsFeature>
 *
 * @method ManagerSettingsFeature|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagerSettingsFeature|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagerSettingsFeature[]    findAll()
 * @method ManagerSettingsFeature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagerSettingsFeatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagerSettingsFeature::class);
    }

    public function save(ManagerSettingsFeature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ManagerSettingsFeature $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ManagerSettingsFeature[] Returns an array of ManagerSettingsFeature objects
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

//    public function findOneBySomeField($value): ?ManagerSettingsFeature
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
