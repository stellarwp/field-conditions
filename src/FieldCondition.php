<?php

namespace StellarWP\FieldConditions;

use InvalidArgumentException;
use StellarWP\FieldConditions\Concerns\HasLogicalOperator;
use StellarWP\FieldConditions\Contracts\Condition;

/**
 * @since 1.0.0
 */
class FieldCondition implements Condition
{
    use HasLogicalOperator;

    const COMPARISON_OPERATORS = ['=', '!=', '>', '>=', '<', '<=', 'contains', 'not_contains'];

    /** @var string */
    const TYPE = 'basic';

    /** @var string */
    protected $field;

    /** @var mixed */
    protected $value;

    /** @var string */
    protected $comparisonOperator;

    protected $strict = false;

    /**
     * Create a new FieldCondition.
     *
     * @since 1.0.0
     *
     * @param mixed $value
     */
    public function __construct(string $field, string $comparisonOperator, $value, string $logicalOperator = 'and')
    {
        if ($this->isInvalidComparisonOperator($comparisonOperator)) {
            throw Config::throwInvalidArgumentException(
                "Invalid comparison operator: $comparisonOperator. Must be one of: " . implode(
                    ', ',
                    self::COMPARISON_OPERATORS
                )
            );
        }

        $this->field = $field;
        $this->comparisonOperator = $comparisonOperator;
        $this->value = $value;
        $this->setLogicalOperator($logicalOperator);
    }

    /**
     * @since 1.0.0
     *
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'type' => static::TYPE,
            'field' => $this->field,
            'value' => $this->value,
            'strictComparison' => $this->strict,
            'comparisonOperator' => $this->comparisonOperator,
            'logicalOperator' => $this->logicalOperator,
        ];
    }

    /**
     * @since 1.0.0
     */
    public function passes(array $values): bool
    {
        if (!isset($values[$this->field])) {
            throw new InvalidArgumentException("Field {$this->field} not found in test values.");
        }

        $testValue = $values[$this->field];

        if ($this->strict && $this->comparisonOperator !== '!=' && gettype($testValue) !== gettype($this->value)) {
            return false;
        }

        switch ($this->comparisonOperator) {
            case '=':
                return $testValue == $this->value;
            case '!=':
                return $this->strict ? $testValue !== $this->value : $testValue != $this->value;
            case '>':
                return $testValue > $this->value;
            case '>=':
                return $testValue >= $this->value;
            case '<':
                return $testValue < $this->value;
            case '<=':
                return $testValue <= $this->value;
            case 'contains':
                return ('' == $this->value || false !== strpos($testValue, $this->value));
            case 'not_contains':
                return ('' == $this->value || false === strpos($testValue, $this->value));
        }
    }

    /**
     * @inheritDoc
     */
    public function fails(array $values): bool
    {
        return !$this->passes($values);
    }

    /**
     * Sets the condition to strict mode.
     *
     * @since 1.1.0
     */
    public function strict(bool $strict = true): self
    {
        $this->strict = $strict;

        return $this;
    }

    /**
     * Check if the provided operator is invalid.
     *
     * @since 1.0.0
     */
    protected function isInvalidComparisonOperator(string $operator): bool
    {
        return !in_array($operator, static::COMPARISON_OPERATORS, true);
    }

    /**
     * Check if the provided boolean is invalid.
     *
     * @since 1.0.0
     */
    protected function isInvalidLogicalOperator(string $operator): bool
    {
        return !in_array($operator, Condition::LOGICAL_OPERATORS, true);
    }
}
