<?php

declare(strict_types=1);

namespace unit\Concerns;

use InvalidArgumentException;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Tests\TestCase;

class HasLogicalOperatorTest extends TestCase
{
    public function testShouldSetAndGetLogicalOperator()
    {
        $instance = new LogicalClass();

        $instance->setLogicalOperator('and');
        self::assertSame('and', $instance->getLogicalOperator());

        $instance->setLogicalOperator('or');
        self::assertSame('or', $instance->getLogicalOperator());
    }

    public function testShouldThrowExceptionWhenSettingInvalidOperator() {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid logical operator');

        $instance = new LogicalClass();
        $instance->setLogicalOperator('invalid');
    }
}

class LogicalClass
{
    use HasLogicalOperator;
}
