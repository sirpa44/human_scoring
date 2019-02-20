<?php
namespace App\DataFixtures;

use App\Entity\ScorerEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ScorerFixture extends Fixture
{
//    private $encoder;
//
//    public function __construct(UserPasswordEncoderInterface $encoder)
//    {
//        $this->encoder = $encoder;
//    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $objectManager
     */
    public function load(ObjectManager $objectManager)
    {
        $scorer = new ScorerEntity();
        $scorer->setUsername('jean-mix');
//        $password = $this->encoder->encodePassword($scorer, 'pass_1234');
        $password = 'pass_1234';
        $scorer->setPassword($password);

        $objectManager->persist($scorer);

        $this->addReference('scorer-fixture', $scorer);

        $objectManager->flush();
    }

}