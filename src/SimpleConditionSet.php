<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions;

use ArrayIterator;
use StellarWP\FieldConditions\Concerns\HasConditions;
use StellarWP\FieldConditions\Contracts\ConditionSet;

/**
 * @implements ConditionSet<FieldCondition>
 * @uses HasConditions<FieldCondition>
 */
class SimpleConditionSet implements ConditionSet
{
    use HasConditions;

    /**
     * @unreleased
     *
     * @param FieldCondition ...$conditions
     *
     * @return void
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
            static function (FieldCondition $condition) {
                return $condition->jsonSerialize();
            },
            $this->conditions
        );
    }

    /**
     * @inheritDoc
     */
    protected static function getBaseConditionClass(): string
    {
        return FieldCondition::class;
    }
}
