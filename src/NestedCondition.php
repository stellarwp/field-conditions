<?php

namespace StellarWP\FieldConditions;

use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Contracts\Condition;

/**
 * A condition that holds and evaluates multiple conditions.
 *
 * @unreleased
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
     * @unreleased
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
     * @unreleased
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
