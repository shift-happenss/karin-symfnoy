<?php

namespace App\Repository;

use App\Entity\ReponseSoumise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseSoumise>
 *
 * @method ReponseSoumise|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseSoumise|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseSoumise[]    findAll()
 * @method ReponseSoumise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseSoumiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseSoumise::class);
    }

//    /**
//     * @return ReponseSoumise[] Returns an array of ReponseSoumise objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReponseSoumise
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
