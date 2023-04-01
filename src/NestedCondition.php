<?php

namespace StellarWP\FieldConditions;

use ArrayIterator;
use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Contracts\ConditionSet;

/**
 * A condition that holds and evaluates multiple conditions.
 *
 * @since 1.1.1 implement the ConditionSet interface
 * @since 1.0.0
 *
 * @uses HasConditions<Condition>
 */
class NestedCondition implements Condition, ConditionSet
{
    use HasLogicalOperator;
    use HasConditions;

    /**
     * The type of condition.
     */
    const TYPE = 'nested';

    /**
     * @since 1.0.0
     *
     * @param Condition[] $conditions
     * @param 'and'|'or' $logicalOperator
     */
    public function __construct(array $conditions = [], string $logicalOperator = 'and')
    {
        $this->conditions = $conditions;
        $this->setLogicalOperator($logicalOperator);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::TYPE,
            'conditions' => $this->conditions,
            'boolean' => $this->logicalOperator,
        ];
    }

    /**
     * @since 1.1.1
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->conditions);
    }
}
