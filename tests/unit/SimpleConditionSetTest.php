<?php

declare(strict_types=1);

use StellarWP\FieldConditions\FieldCondition;
use StellarWP\FieldConditions\SimpleConditionSet;
use StellarWP\FieldConditions\Tests\TestCase;

class SimpleConditionSetTest extends TestCase
{
    /**
     * @since 1.0.0
     */
    public function testShouldRunAndPassConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(true);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new SimpleConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    /**
     * @since 1.0.0
     */
    public function testShouldRunAndPassConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('or');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new SimpleConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    /**
     * @since 1.0.0
     */
    public function testShouldRunAndFailConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new SimpleConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->fails([]));
    }

    /**
     * @since 1.0.0
     */
    public function testShouldRunAndFailConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(false);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new SimpleConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->fails([]));
    }

    /**
     * @since 1.0.0
     */
    public function testShouldReturnConditions()
    {
        $condition = $this->createMock(FieldCondition::class);
        $conditionSet = new SimpleConditionSet($condition);

        $this->assertSame([$condition], $conditionSet->getConditions());
    }

    /**
     * @since 1.0.0
     */
    public function testShouldAddConditions()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition2 = $this->createMock(FieldCondition::class);
        $conditionSet = new SimpleConditionSet($condition1);

        $conditionSet->append($condition2);

        $this->assertSame([$condition1, $condition2], $conditionSet->getConditions());
    }

    /**
     * @since 1.0.0
     */
    public function testShouldReturnJsonSerializedConditions()
    {
        $condition = $this->createMock(FieldCondition::class);
        $condition->method('jsonSerialize')->willReturn(['foo' => 'bar']);
        $conditionSet = new SimpleConditionSet($condition);

        $this->assertSame([['foo' => 'bar']], $conditionSet->jsonSerialize());
    }

    /**
     * @since 1.0.0
     */
    public function testShouldAllowIterationOverConditions()
    {
        $condition = $this->createMock(FieldCondition::class);
        $conditionSet = new SimpleConditionSet($condition);

        foreach($conditionSet as $condition) {
            self::assertInstanceOf(FieldCondition::class, $condition);
        }
    }
}
