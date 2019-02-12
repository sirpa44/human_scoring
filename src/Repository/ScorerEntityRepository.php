<?php
namespace App\Repository;

use App\Entity\ScorerEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScorerEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScorerEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScorerEntity[]    findAll()
 * @method ScorerEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScorerEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScorerEntity::class);
    }

    // /**
    //  * @return ScorerEntity[] Returns an array of ScorerEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScorerEntity
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
