<?php

namespace App\Tests;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\ProxyReferenceRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseWebTestCase extends WebTestCase
{
    /**
     * @var null|Client
     */
    private $client = null;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SymfonyFixturesLoader
     */
    private $fixtureLoader;

    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        if (is_null($this->em)) {
            $this->em = self::$kernel->getContainer()
                ->get('doctrine')
                ->getManager();
        }

        return $this->em;
    }

    /**
     * Adds a DoctrineFixture to be loaded.
     * @param FixtureInterface $fixture
     */
    protected function addFixture(FixtureInterface $fixture): void
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }

    /**
     * Loads the added DoctrineFixtures
     */
    protected function loadFixtures(): void
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    /**
     * Purges the DB.
     *
     * @throws \Exception
     */
    protected function truncateEntities(): void
    {
        $this->getFixtureExecutor()->purge();
    }

    /**
     * Gets a doctrine entity by its reference used in the fixture file.
     *
     * @param string $name
     * @return object
     */
    protected function getFixtureReference(string $name)
    {
        return $this->getFixtureExecutor()->getReferenceRepository()->getReference($name);
    }

    private function getFixtureLoader(): SymfonyFixturesLoader
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new SymfonyFixturesLoader(self::$kernel->getContainer());
        }

        return $this->fixtureLoader;
    }

    private function getFixtureExecutor(): ORMExecutor
    {
        if (!$this->fixtureExecutor) {
            $this->fixtureExecutor = new ORMExecutor(
                $this->getEntityManager(), new ORMPurger($this->getEntityManager())
            );

            $referenceRepository = new ProxyReferenceRepository($this->getEntityManager());

            $this->fixtureExecutor->setReferenceRepository($referenceRepository);
        }

        return $this->fixtureExecutor;
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (!is_null($this->em)) {
            $this->em->close();
            $this->em = null; // avoid memory leaks
        }
    }

    protected function getClient(): ?Client
    {
        return $this->client;
    }

    protected function logInAs(UserInterface $user, $role = 'ROLE_USER'): void
    {
        $session = $this->client->getContainer()->get('session');

        // the firewall context defaults to the firewall name
        $firewallContext = 'main';

        $token = new UsernamePasswordToken($user, $user->getUsername(), $firewallContext, array($role));
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    protected function requestAsAjax($method, $uri, array $parameters = array(), array $files = array(), array $server = array(), $content = null, $changeHistory = true)
    {
        $this->getClient()->request(
            $method,
            $uri,
            $parameters,
            $files,
            array_merge($server, ['CONTENT_TYPE' => 'application/json', 'HTTP_X-Requested-With' => 'XMLHttpRequest']),
            $content,
            $changeHistory
        );
    }
}