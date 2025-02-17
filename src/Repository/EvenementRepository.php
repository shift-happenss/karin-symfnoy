<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @return Evenement[] Returns an array of Evenement objects
     */
    public function findAllWithPaginationAndFilter($page, $limit, $type = null, $dateDebut = null, $dateFin = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.dateDebut', 'DESC');
        
        if ($type) {
            $qb->andWhere('e.type LIKE :type')
                ->setParameter('type', '%'.$type.'%');
        }

        if ($dateDebut) {
            $qb->andWhere('e.dateDebut >= :dateDebut')
                ->setParameter('dateDebut', $dateDebut);
        }

        if ($dateFin) {
            $qb->andWhere('e.dateFin <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }

        return $qb;
    }

    // Autres méthodes existantes...
}
