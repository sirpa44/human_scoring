<?php
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
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
}
