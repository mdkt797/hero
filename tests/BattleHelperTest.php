<?php

use PHPUnit\Framework\TestCase;
use Services\BattleServices;

/**
 * Class BattleHelperTest
 */
class BattleHelperTest extends TestCase
{

    protected $testClass;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testClass = new \Services\BattleServices();
    }

    public function testInitializeStats()
    {

        $this->assertThat(
            $this->testClass->initializeStats(['Health' => [10, 20]])['Health'],
            $this->logicalAnd(
                $this->greaterThan(9.99),
                $this->lessThan(20.01)
            ),
            'Initialization of stats failed. Bad return value'
        );
    }

    public function testCalculateDamage()
    {
        $this->assertContains(
            $this->testClass->calculateDamage('monster', 50, 40, 1),
            [10, 5]
        );

        $this->assertContains(
            $this->testClass->calculateDamage('hero', 50, 40, 1),
            [10, 20]
        );
    }

    public function testDecideFirstCall()
    {
        $instance = $this->testClass->setupScene();

        self::assertInstanceOf(BattleServices::class, $instance);

        $this->assertContains(
            $instance->firstCall,
            ['hero', 'monster']
        );

        $this->assertContains(
            $instance->secondCall,
            ['hero', 'monster']
        );
    }

    public function testBattle()
    {
        $result = $this->testClass->setupScene()->battleBegins();

        $this->assertContains(
            $result,
            ['monster' , 'hero' , 'Both of them got bored']
        );
    }
}