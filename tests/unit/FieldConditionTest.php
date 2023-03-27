<?php

declare(strict_types=1);

use StellarWP\FieldConditions\FieldCondition;
use StellarWP\FieldConditions\Tests\TestCase;

class FieldConditionTest extends TestCase
{
    /**
     * @since 1.0.0
     *
     * @dataProvider passConditionsDataProviders
     */
    public function testPassingConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new FieldCondition('field', $comparisonOperator, $conditionalValue);

        $this->assertTrue($condition->passes(['field' => $valueToCompare]));
    }

    /**
     * @since 1.1.0 add loosely equal conditions
     * @since 1.0.0
     */
    public function passConditionsDataProviders(): array
    {
        return [
            ['foo', '=', 'foo'],
            [10, '=', '10.00'],
            ['foo', '!=', 'bar'],
            [5, '>', 1],
            [10, '>', '1'],
            [5, '>=', 5],
            [1, '<', 5],
            [5, '<=', 5],
            ['foo', 'contains', 'oo'],
            ['1234', 'contains', '3'],
            ['foo', 'not_contains', 'bar'],
        ];
    }

    /**
     * @since 1.1.0
     *
     * @dataProvider passStrictConditionsDataProviders
     */
    public function testPassingStrictConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new FieldCondition('field', $comparisonOperator, $conditionalValue);
        $condition->strict();

        $this->assertTrue($condition->passes(['field' => $valueToCompare]));
    }

    public function passStrictConditionsDataProviders(): array
    {
        return [
            [10, '=', 10],
            [10, '!=', '10'],
            [10, '>', 5],
            [10, '>=', 10],
            [10, '<', 15],
            [10, '<=', 10],
        ];
    }


    /**
     * @since 1.0.0
     *
     * @dataProvider failConditionsDataProviders
     */
    public function testFailingConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new FieldCondition('field', $comparisonOperator, $conditionalValue);

        $this->assertTrue($condition->fails(['field' => $valueToCompare]));
    }

    /**
     * @since 1.0.0
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
     * @since 1.1.0
     *
     * @dataProvider failStrictConditionsDataProviders
     */
    public function testFailingStrictConditions($valueToCompare, $comparisonOperator, $conditionalValue)
    {
        $condition = new FieldCondition('field', $comparisonOperator, $conditionalValue);
        $condition->strict();

        $this->assertTrue($condition->fails(['field' => $valueToCompare]));
    }

    /**
     * @since 1.1.0
     */
    public function failStrictConditionsDataProviders(): array
    {
        return [
            [10, '=', '10'],
            [10, '!=', 10],
            [10, '>', 15],
            [10, '>=', 15],
            [10, '<', 5],
            [10, '<=', 5],
            ['2', 'contains', 2],
            ['2', 'not_contains', '2'],
        ];
    }

    /**
     * @since 1.0.0
     */
    public function testConditionSerialization()
    {
        $condition = new FieldCondition('field', '=', 'foo');

        $this->assertEquals([
            'type' => 'basic',
            'field' => 'field',
            'comparisonOperator' => '=',
            'strictComparison' => false,
            'value' => 'foo',
            'logicalOperator' => 'and',
        ], $condition->jsonSerialize());
    }

    /**
     * @since 1.0.0
     */
    public function testGettingLogicalOperator()
    {
        $condition = new FieldCondition('field', '=', 'foo', 'or');

        $this->assertEquals('or', $condition->getLogicalOperator());
    }

    public function testConstructorThrowsExceptionForInvalidComparisonOperator()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid comparison operator');

        new FieldCondition('field', 'foo', 'bar');
    }

    public function testConstructorThrowsExceptionForInvalidLogicalOperator()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logical operator');

        new FieldCondition('field', '=', 'bar', 'foo');
    }
}
