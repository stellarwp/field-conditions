<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Concerns;

use StellarWP\FieldConditions\Config;
use StellarWP\FieldConditions\Contracts\Condition;

trait HasLogicalOperator
{
    /**
     * @var 'and'|'or'
     */
    protected $logicalOperator;

    /**
     * @unreleased
     *
     * @return void
     */
    public function setLogicalOperator(string $operator)
    {
        if ( ! in_array($operator, Condition::LOGICAL_OPERATORS, true)) {
            throw Config::throwInvalidArgumentException(
                "Invalid logical operator: $operator. Must be one of: " . implode(
                    ', ',
                    Condition::LOGICAL_OPERATORS
                )
            );
        }

        $this->logicalOperator = $operator;
    }

    /**
     * @unreleased
     */
    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }
}
