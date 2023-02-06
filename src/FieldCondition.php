<?php

namespace StellarWP\FieldConditions;

use InvalidArgumentException;
use StellarWP\FieldConditions\Contracts\Condition;

/**
 * @since 2.13.0
 */
class FieldCondition implements Condition
{
    const COMPARISON_OPERATORS = ['=', '!=', '>', '>=', '<', '<=', 'contains', 'not_contains'];

    /** @var string */
    const TYPE = 'basic';

    /** @var string */
    protected $field;

    /** @var mixed */
    protected $value;

    /** @var string */
    protected $comparisonOperator;

    /**
     * @var 'and'|'or'
     */
    protected $logicalOperator;

    /**
     * Create a new FieldCondition.
     *
     * @unreleased
     *
     * @param mixed $value
     */
    public function __construct(string $field, string $comparisonOperator, $value, string $logicalOperator = 'and')
    {
        if ($this->isInvalidComparisonOperator($comparisonOperator)) {
            throw Config::throwInvalidArgumentException(
                "Invalid comparison operator: $comparisonOperator. Must be one of: " . implode(', ', self::COMPARISON_OPERATORS)
            );
        }

        if ($this->isInvalidLogicalOperator($logicalOperator)) {
            throw Config::throwInvalidArgumentException(
                "Invalid logical operator: $logicalOperator. Must be one of: " . implode(', ', Condition::LOGICAL_OPERATORS)
            );
        }

        $this->field = $field;
        $this->comparisonOperator = $comparisonOperator;
        $this->value = $value;
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::TYPE,
            'field' => $this->field,
            'value' => $this->value,
            'comparisonOperator' => $this->comparisonOperator,
            'logicalOperator' => $this->logicalOperator,
        ];
    }

    public function passes(array $values): bool
    {
        if ( ! isset($values[$this->field])) {
            throw new InvalidArgumentException("Field {$this->field} not found in test values.");
        }

        $testValue = $values[$this->field];

        switch ($this->comparisonOperator) {
            case '=':
                return $testValue === $this->value;
            case '!=':
                return $testValue !== $this->value;
            case '>':
                return $testValue > $this->value;
            case '>=':
                return $testValue >= $this->value;
            case '<':
                return $testValue < $this->value;
            case '<=':
                return $testValue <= $this->value;
            case 'contains':
                return ('' === $this->value || false !== strpos($testValue, $this->value));
            case 'not_contains':
                return ('' === $this->value || false === strpos($testValue, $this->value));
        }
    }

    /**
     * @inheritDoc
     */
    public function fails(array $values): bool
    {
        return ! $this->passes($values);
    }

    /**
     * @inheritDoc
     */
    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }

    /**
     * Check if the provided operator is invalid.
     *
     * @since 2.13.0
     */
    protected function isInvalidComparisonOperator(string $operator): bool
    {
        return ! in_array($operator, static::COMPARISON_OPERATORS, true);
    }

    /**
     * Check if the provided boolean is invalid.
     *
     * @since 2.13.0
     */
    protected function isInvalidLogicalOperator(string $operator): bool
    {
        return ! in_array($operator, Condition::LOGICAL_OPERATORS, true);
    }
}
