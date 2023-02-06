<?php

declare(strict_types=1);

use StellarWP\FieldConditions\BasicCondition;
use StellarWP\FieldConditions\Tests\TestCase;

class BasicConditionTest extends TestCase
{
    /**
     * @unreleased
     *
     * @dataProvider passConditionsDataProviders
     */
    public function testPassingConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new BasicCondition('field', $comparisonOperator, $conditionalValue);

        $this->assertTrue($condition->passes(['field' => $valueToCompare]));
    }

    /**
     * @unreleased
     */
    public function passConditionsDataProviders(): array
    {
        return [
            ['foo', '=', 'foo'],
            ['foo', '!=', 'bar'],
            [5, '>', 1],
            [5, '>=', 5],
            [1, '<', 5],
            [5, '<=', 5],
            ['foo', 'contains', 'oo'],
            ['foo', 'not_contains', 'bar'],
        ];
    }

    /**
     * @unreleased
     *
     * @dataProvider failConditionsDataProviders
     */
    public function testFailingConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new BasicCondition('field', $comparisonOperator, $conditionalValue);

        $this->assertTrue($condition->fails(['field' => $valueToCompare]));
    }

    /**
     * @unreleased
     */
    public function failConditionsDataProviders(): array
    {
        return [
            ['foo', '=', 'bar'],
            ['foo', '!=', 'foo'],
            [5, '>', 10],
            [5, '>=', 10],
            [1, '<', 0],
            [5, '<=', 0],
            ['foo', 'contains', 'bar'],
            ['foo', 'not_contains', 'foo'],
        ];
    }

    /**
     * @unreleased
     */
    public function testConditionSerialization()
    {
        $condition = new BasicCondition('field', '=', 'foo');

        $this->assertEquals([
            'type' => 'basic',
            'field' => 'field',
            'comparisonOperator' => '=',
            'value' => 'foo',
            'logicalOperator' => 'and',
        ], $condition->jsonSerialize());
    }

    /**
     * @unreleased
     */
    public function testGettingLogicalOperator()
    {
        $condition = new BasicCondition('field', '=', 'foo', 'or');

        $this->assertEquals('or', $condition->getLogicalOperator());
    }

    public function testConstructorThrowsExceptionForInvalidComparisonOperator()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid comparison operator');

        new BasicCondition('field', 'foo', 'bar');
    }

    public function testConstructorThrowsExceptionForInvalidLogicalOperator()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logical operator');

        new BasicCondition('field', '=', 'bar', 'foo');
    }
}
