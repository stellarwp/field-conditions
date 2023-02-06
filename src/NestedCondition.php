<?php

namespace StellarWP\FieldConditions;

use StellarWP\FieldConditions\Contracts\Condition;

/**
 * @since 2.13.0
 */
class NestedCondition implements Condition
{

    /** @var string */
    const TYPE = 'nested';

    /** @var Condition[] */
    protected $conditions = [];

    /**
     * @var 'and'|'or'
     */
    protected $logicalOperator;


    /**
     * @unreleased
     *
     * @param Condition[] $conditions
     * @param 'and'|'or' $logicalOperator
     */
    public function __construct(array $conditions, string $logicalOperator = 'and')
    {
        $this->conditions = $conditions;
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function passes(array $values): bool
    {
        return array_reduce(
            $this->conditions,
            static function ($carry, $condition) use ($values) {
                return $condition->boolean === 'and'
                    ? $carry && $condition->passes($values)
                    : $carry || $condition->passes($values);
            },
            true
        );
    }

    /**
     * @inheritDoc
     */
    public function fails(array $values): bool
    {
        return ! $this->passes($values);
    }

    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }
}
