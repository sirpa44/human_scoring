<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Test\Controller;

use App\Tests\BaseWebTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeControllerTest extends BaseWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $scorerEntity = self::$container->get('App\DataFixtures\ScorerFixture');
        $this->addFixture($scorerEntity);
        $this->loadFixtures();
    }

    public function testSecurityRedirection()
    {
        $this->getClient()->request('GET', '/');
        $this->assertTrue($this->getClient()->getResponse()->isRedirect('/login'));
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