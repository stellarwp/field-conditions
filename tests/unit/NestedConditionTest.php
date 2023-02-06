<?php

declare(strict_types=1);

use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\NestedCondition;
use StellarWP\FieldConditions\Tests\TestCase;

class NestedConditionTest extends TestCase
{
    /**
     * @unreleased
     */
    public function testShouldRunAndPassConditionsWithLogicalAnd()
    {
        $values = ['foo' => 'bar'];

        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with($values)->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with($values)->willReturn(true);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $condition = new NestedCondition([$condition1, $condition2], 'and');

        $this->assertTrue($condition->passes($values));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndPassConditionsWithLogicalOr()
    {
        $values = ['foo' => 'bar'];

        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with($values)->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with($values)->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('or');

        $condition = new NestedCondition([$condition1, $condition2], 'and');

        $this->assertTrue($condition->passes($values));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndFailConditionsWithLogicalAnd()
    {
        $values = ['foo' => 'bar'];

        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with($values)->willReturn(true);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with($values)->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $condition = new NestedCondition([$condition1, $condition2], 'and');

        self::assertTrue($condition->fails($values));
    }

    /**
     * @unreleased
     */
    public function testShouldRunAndFailConditionsWithLogicalOr()
    {
        $values = ['foo' => 'bar'];

        $condition1 = $this->createMock(Condition::class);
        $condition1->method('passes')->with($values)->willReturn(false);
        $condition1->method('getLogicalOperator')->willReturn('and');

        $condition2 = $this->createMock(Condition::class);
        $condition2->method('passes')->with($values)->willReturn(false);
        $condition2->method('getLogicalOperator')->willReturn('and');

        $condition = new NestedCondition([$condition1, $condition2], 'and');

        self::assertTrue($condition->fails($values));
    }

    /**
     * @unreleased
     */
    public function testShouldGetLogicalOperator()
    {
        $condition = new NestedCondition([], 'and');

        $this->assertSame('and', $condition->getLogicalOperator());
    }

    /**
     * @unreleased
     */
    public function testShouldReturnSerializedCondition()
    {
        $condition = new NestedCondition([], 'and');

        $this->assertSame(
            [
                'type' => NestedCondition::TYPE,
                'conditions' => [],
                'boolean' => 'and',
            ],
            $condition->jsonSerialize()
        );
    }

    /**
     * @unreleased
     */
    public function testShouldThrowExceptionWhenLogicalOperatorIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logical operator');

        new NestedCondition([], 'invalid');
    }
}
