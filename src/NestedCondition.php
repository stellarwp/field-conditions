<?php

namespace StellarWP\FieldConditions;

use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Contracts\Condition;

/**
 * A condition that holds and evaluates multiple conditions.
 *
 * @since 1.0.0
 *
 * @uses HasConditions<Condition>
 */
class NestedCondition implements Condition
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
}
