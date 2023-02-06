<?php

namespace StellarWP\FieldConditions;

use StellarWP\FieldConditions\Contracts\Condition;

/**
 * A condition that holds and evaluates multiple conditions.
 *
 * @unreleased
 */
class NestedCondition implements Condition
{

    /**
     * The type of condition.
     */
    const TYPE = 'nested';

    /**
     * @var array<Condition>
     */
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

        if ( ! in_array($logicalOperator, Condition::LOGICAL_OPERATORS, true)) {
            throw Config::throwInvalidArgumentException(
                "Invalid logical operator: $logicalOperator. Must be one of: " . implode(
                    ', ',
                    Condition::LOGICAL_OPERATORS
                )
            );
        }

        $this->logicalOperator = $logicalOperator;
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

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function passes(array $values): bool
    {
        return array_reduce(
            $this->conditions,
            static function ($carry, Condition $condition) use ($values) {
                return $condition->getLogicalOperator() === 'and'
                    ? $carry && $condition->passes($values)
                    : $carry || $condition->passes($values);
            },
            true
        );
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function fails(array $values): bool
    {
        return ! $this->passes($values);
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }
}
