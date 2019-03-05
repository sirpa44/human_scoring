<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\DataFixtures;

use App\Entity\ScorerEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ScorerFixture extends Fixture
{
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $objectManager
     */
    public function load(ObjectManager $objectManager)
    {
        $scorer = new ScorerEntity();
        $scorer->setUsername('jean-mix');
        $scorer->setPassword($this->encoder->encodePassword($scorer, 'pass_1234'));
        $this->addReference('scorer-fixture', $scorer);
        $objectManager->persist($scorer);
        $objectManager->flush();
    }

}