<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions;

use ArrayIterator;
use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Contracts\ConditionSet;

/**
 * @implements ConditionSet<Condition>
 * @uses HasConditions<Condition>
 */
class ComplexConditionSet implements ConditionSet
{
    use HasConditions;

    /**
     * @since 1.0.0
     *
     * @param Condition ...$conditions
     */
    public function __construct(...$conditions)
    {
        $this->validateConditions($conditions);
        $this->conditions = $conditions;
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * @inheritDoc
     *
     * @since 1.0.0
     */
    public function jsonSerialize(): array
    {
        return array_map(
            static function (Condition $condition) {
                return $condition->jsonSerialize();
            },
            $this->conditions
        );
    }
}
