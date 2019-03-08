<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Repository;
use App\Entity\Scorer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Scorer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scorer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scorer[]    findAll()
 * @method Scorer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScorerEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Scorer::class);
    }

}
