<?php

declare(strict_types=1);

namespace unit\Concerns;

use InvalidArgumentException;
use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\FieldCondition;
use StellarWP\FieldConditions\NestedCondition;
use StellarWP\FieldConditions\Tests\TestCase;
class HasConditionsTest extends TestCase
{
    public function testShouldAppendAndGetConditions()
    {
        $conditionSet = new ConditionSet();
        $conditionSet->append(
            $this->createMock(Condition::class),
            $this->createMock(Condition::class)
        );

        self::assertCount(2, $conditionSet->getConditions());
    }

    public function testShouldAddAndConditionUsingWhereMethod()
    {
        $conditionSet = new ConditionSet();
        $conditionSet->where('foo', '=', 'bar');

        self::assertCount(1, $conditionSet->getConditions());
        self::assertInstanceOf(FieldCondition::class, $conditionSet->getConditions()[0]);
        self::assertSame('and', $conditionSet->getConditions()[0]->getLogicalOperator());
    }

    public function testShouldAddAndConditionUsingAndMethod()
    {
        $conditionSet = new ConditionSet();
        $conditionSet->and('foo', '=', 'bar');

        self::assertCount(1, $conditionSet->getConditions());
        self::assertInstanceOf(FieldCondition::class, $conditionSet->getConditions()[0]);
        self::assertSame('and', $conditionSet->getConditions()[0]->getLogicalOperator());
    }

    public function testShouldAddOrConditionUsingOrMethod()
    {
        $conditionSet = new ConditionSet();
        $conditionSet->or('foo', '=', 'bar');

        self::assertCount(1, $conditionSet->getConditions());
        self::assertInstanceOf(FieldCondition::class, $conditionSet->getConditions()[0]);
        self::assertSame('or', $conditionSet->getConditions()[0]->getLogicalOperator());
    }

    public function testShouldChainConditions()
    {
        $conditionSet = new ConditionSet();
        $conditionSet
            ->where('foo', '=', 'bar')
            ->and('bar', '=', 'baz')
            ->or('baz', '=', 'foo');

        self::assertCount(3, $conditionSet->getConditions());
    }

    public function testShouldAddNestedConditionViaClosure()
    {
        $conditionSet = new ConditionSet();
        $conditionSet->where(function (NestedCondition $condition) {
            $condition->where('foo', '=', 'bar');
        });

        self::assertCount(1, $conditionSet->getConditions());
        self::assertInstanceOf(NestedCondition::class, $conditionSet->getConditions()[0]);
        self::assertCount(1, $conditionSet->getConditions()[0]->getConditions());
    }

    public function testShouldRunAndPassConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(true);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new ConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndPassConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('or');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new ConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->passes([]));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndFailConditionsWithLogicalAnd()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $conditionSet = new ConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->fails([]));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndFailConditionsWithLogicalOr()
    {
        $condition1 = $this->createMock(FieldCondition::class);
        $condition1->method('passes')->with([])->willReturn(false);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(FieldCondition::class);
        $condition2->method('passes')->with([])->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $conditionSet = new ConditionSet($condition1, $condition2);

        self::assertTrue($conditionSet->fails([]));
    }

    public function testShouldThrownAnExceptionWhenAppendingAnInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Condition must be an instance');

        $conditionSet = new ConditionSet();
        $conditionSet->append('foo');
    }

    public function testShouldAllowConditionsWithTheBaseClass()
    {
        $conditionSet = new ConditionSetWithDifferentBaseClass();
        $conditionSet->append($this->createMock(MockCondition::class));

        self::assertCount(1, $conditionSet->getConditions());
    }

    public function testShouldThrownAnExceptionWhenTheBaseClassChangesAndAnInvalidArgumentIsUsed()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Condition must be an instance');

        $conditionSet = new ConditionSetWithDifferentBaseClass();
        $conditionSet->append($this->createMock(NestedCondition::class));
    }
}

class ConditionSet {
    use HasConditions;

    public function __construct(...$conditions)
    {
        $this->conditions = $conditions;
    }
}

class ConditionSetWithDifferentBaseClass {
    use HasConditions;

    public function __construct(...$conditions)
    {
        $this->conditions = $conditions;
    }

    protected static function getBaseConditionClass(): string
    {
        return MockCondition::class;
    }
}

class MockCondition implements Condition {
    use HasLogicalOperator;

    public function passes(array $values): bool
    {
        return true;
    }

    public function fails(array $values): bool
    {
        return false;
    }

    public function jsonSerialize()
    {
        return null;
    }
}
