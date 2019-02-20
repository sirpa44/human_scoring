<?php
namespace App\Test\Controller;

use App\DataFixtures\ScorerFixture;
use App\Tests\BaseWebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeControllerTest extends BaseWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->addFixture(new ScorerFixture());
        $this->loadFixtures();
    }

    public function testSecurityRedirection()
    {
        $this->getClient()->request('GET', '/');
        $this->assertTrue($this->getClient()->getResponse()->isRedirect('/login'));
        $this->assertContains('login', $this->getClient()->getResponse()->headers->get('Location'));
    }

    public function testLoginAsJeanMix()
    {
        $user = $this->getFixtureReference('scorer-fixture');
        $this->logInAs($user, 'Scorer');
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testNoSecurityRedirectionForLogin()
    {
        $user = $this->getFixtureReference('scorer-fixture');
        $this->logInAs($user, 'Scorer');
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->getClient()->request('GET', '/');

        $this->assertContains($user->getUsername(), $this->getClient()->getResponse()->getContent());
    }
}