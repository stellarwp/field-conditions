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
     * @unreleased
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
     * @unreleased
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * @inheritDoc
     *
     * @unreleased
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
