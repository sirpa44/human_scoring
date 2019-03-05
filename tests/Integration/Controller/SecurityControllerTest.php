<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\Tests\Integration\Controller;

use App\DataFixtures\ScorerFixture;
use App\Tests\BaseWebTestCase;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityControllerTest extends BaseWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $scorerEntity = self::$container->get('App\DataFixtures\ScorerFixture');
        $this->addFixture($scorerEntity);
        $this->loadFixtures();
    }

    public function testLoginWithLastUserName()
    {
        $session = $this->getClient()->getContainer()->get('session');
        $session->set(Security::LAST_USERNAME, 'User-_-Scorer1344');
        $session->save();
        $this->assertContains('User-_-Scorer1344', $this->getClient()->request('GET', '/login')->html());
    }

    public function testLoginWithAuthenticationError()
    {
        $session = $this->getClient()->getContainer()->get('session');
        $session->set(Security::AUTHENTICATION_ERROR, 'ERROR_SHOULD_BE_THERE');
        $session->save();
        $this->assertContains('ERROR_SHOULD_BE_THERE', $this->getClient()->request('GET', '/login')->html(), 'remonter gastrique');
    }

    public function testLogout()
    {
        $user = $this->getFixtureReference('scorer-fixture');
        $this->assertInstanceOf(UserInterface::class, $user);
        $this->logInAs($user, 'Scorer');

        // check if session is ok
        $this->getClient()->request('GET', '/');
        $this->assertContains($user->getUsername(), $this->getClient()->getResponse()->getContent());

        //logout
        $this->getClient()->request('GET', '/logout');

        // recheck session to know if no session
        $this->getClient()->request('GET', '/');
        $this->assertTrue($this->getClient()->getResponse()->isRedirect('/login'));
        $this->assertContains('login', $this->getClient()->getResponse()->headers->get('Location'));
    }
}