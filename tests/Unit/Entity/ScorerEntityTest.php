<?php declare(strict_types = 1);
/**
 * Human Scoring Software
 *
 * @author antoinep@taotesting.com
 * @license See LICENCE.md
 */
namespace App\tests\Unit\Tests\Entity;

use App\Entity\ScorerEntity;
use PHPUnit\Framework\TestCase;

class ScorerEntityTest extends TestCase
{
    /**
     * @param $fixture
     * @throws \ReflectionException
     *
     * @dataProvider setGetUsernameProvider
     */
    public function testSetGetUsername($fixture)
    {
        $entity = new ScorerEntity();
        $this->assertInstanceOf(
            ScorerEntity::class,
            $entity->setUsername($fixture)
        );

        $property = new \ReflectionProperty(ScorerEntity::class, 'username');
        $property->setAccessible(true);

        $this->assertEquals($fixture, $property->getValue($entity), 'Internal username attribute is not valid');
        $this->assertEquals($fixture, $entity->getUsername(), 'The output of getUsername is not valid');
    }

    public function setGetUsernameProvider()
    {
        return [
            ['toto-fixture'],
            [123456],
            [false],
            [null],
            [''],
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function testSetPassword()
    {
        $entity = new ScorerEntity();
        $entity->setPassword('1234qwer');

        $property = new \ReflectionProperty(ScorerEntity::class, 'password');
        $property->setAccessible(true);

        $this->assertEquals('1234qwer', $property->getValue($entity));
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetPassword()
    {
        $entity = new ScorerEntity();

        $property = new \ReflectionProperty(ScorerEntity::class, 'password');
        $property->setAccessible(true);
        $property->setValue($entity, '1234qwer');

        $this->assertEquals('1234qwer', $entity->getPassword());
    }

    /**
     * @throws \ReflectionException
     */
    public function testGetId()
    {
        $entity = new ScorerEntity();

        $property = new \ReflectionProperty(ScorerEntity::class, 'id');
        $property->setAccessible(true);
        $property->setValue($entity, 55);

        $this->assertEquals(55, $entity->getId());
    }


    public function testGetRole()
    {
        $entity = new ScorerEntity();
        $this->assertEquals(['Scorer'], $entity->getRoles());
    }

    public function testGetSalt()
    {
        $entity = new ScorerEntity();
        $this->assertEquals(null, $entity->getSalt());
    }

    public function testEraseCredentials()
    {
        $entity = new ScorerEntity();
        $this->assertEquals(null, $entity->eraseCredentials());
    }
}
