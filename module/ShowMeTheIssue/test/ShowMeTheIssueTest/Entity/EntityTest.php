<?php
namespace ShowMeTheIssueTest\Entity;

use PHPUnit_Framework_TestCase;
use ShowMeTheIssue\Entity\Issue;

/**
 * Issue Entity Test
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Issue
     */
    protected $entity;

    protected $initArray = [
        'id' => 1,
        'title' => 'Bug in when creating a product',
        'description' => 'When submiting a form I get an error',
        'asignee' => 'Mr. Esquimal',
        'created_at' => '2014-05-01',
        'updated_at' => '1014-05-02',
        'kind' => 'bug',
        'priority' => 'high',
        'status' => 'open'
    ];

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->entity = new \ShowMeTheIssue\Entity\Issue();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->entity = null;
    }

    /**
     * Test that all properties are defined
     */
    public function testPublicProperties()
    {
        foreach ($this->initArray as $k => $v) {
            $this->assertObjectHasAttribute($k, $this->entity);
        }
    }

    /**
     * Test that the object is hidrated correctly
     */
    public function testExchangeArray()
    {
        $this->entity->exchangeArray($this->initArray);
        foreach ($this->initArray as $k => $v) {
            $this->assertEquals($this->entity->$k, $v);
        }
    }

    /**
     * Test that it generates a correct array
     */
    public function testGetArrayCopy()
    {
        $this->entity->exchangeArray($this->initArray);
        $array = $this->entity->getArrayCopy();
        $this->assertEquals($this->initArray, $array);
    }

    /**
     * Test that it generates a valid json
     */
    public function testJsonSerialize()
    {
        $this->entity->exchangeArray($this->initArray);
        $jsonArray = $this->entity->jsonSerialize();
        $this->assertEquals($this->entity->getArrayCopy(), $jsonArray);
    }
}
