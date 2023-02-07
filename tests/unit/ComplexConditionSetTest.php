<?php

declare(strict_types=1);

namespace unit;

use StellarWP\FieldConditions\ComplexConditionSet;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Tests\TestCase;

class ComplexConditionSetTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldRunAndPassConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with([])->willReturn(true);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndPassConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('or');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    public function testShouldRunAndFailConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertFalse($conditionSet->passes([]));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndFailConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with([])->willReturn(false);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertFalse($conditionSet->passes([]));
    }

    /**
     * @unreleased
     */
    public function testShouldReturnConditions()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition2 = $this->createMock(Condition::class);

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertSame([$condition1, $condition2], $conditionSet->getConditions());
    }

    /**
     * @unreleased
     */
    public function testShouldAddConditions()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition2 = $this->createMock(Condition::class);

        $conditionSet = new ComplexConditionSet($condition1);
        $conditionSet->addConditions($condition2);

        self::assertSame([$condition1, $condition2], $conditionSet->getConditions());
    }

    public function testShouldReturnJsonSerializedConditions()
    {
        $condition1 = $this->createMock(Condition::class);
        $condition1->method('jsonSerialize')->willReturn(['foo' => 'bar']);

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('jsonSerialize')->willReturn(['bar' => 'baz']);

        $conditionSet = new ComplexConditionSet($condition1, $condition2);

        self::assertSame(
            [
                ['foo' => 'bar'],
                ['bar' => 'baz'],
            ],
            $conditionSet->jsonSerialize()
        );
    }
}
