# Introduction

This is a small PHP library for defining and processing field conditions. By "field" conditions
we're referring to conditions for use with a set of fields, such as a form. A field condition
consists of a field name, a comparison operator, the value to compare against, and the logical
operator to use when combining multiple conditions. For example:

```php
$condition = new FieldCondition('name', '=', 'John', 'and');
```

This condition would be true if the value of the field named "name" is equal to "John".

Finally, all conditions can be serialized into JSON. The intended scenario is for passing
said conditions to the front-end to be used in JavaScript. Simply use the `json_encode()`
function to serialize the condition or condition set.

## Installation

It is recommended to install this library using [Composer](https://getcomposer.org/). To do so, run
the following command:

```bash
composer require stellarwp/field-conditions
```

If using this in WordPress, it is strongly recommended that
you [use Strauss](https://github.com/stellarwp/global-docs/blob/main/docs/strauss-setup.md)
to avoid conflicts with other plugins.

### Configuration

The library includes a `Config` class which can be used for setting configuration options. At
this time, the only configuration is the ability to override the `InvalidArgumentException`, in
case you need your own exception to be used here.

```php
use StellarWP\FieldConditions\Config;

Config::setInvalidArgumentExceptionClass(MyInvalidArgumentException::class);
```

## How to use

Typically, conditions will be stored within a `ConditionSet` object. This object tracks the
conditions and provides methods for determining whether the conditions pass or fail a given
set of data.

There are two types of conditions:

- `FieldCondition` - A condition that compares a field value to a given value.
- `NestedCondition` - A condition that contains other conditions.

There are two types of condition sets:

- `SimpleConditionSet` - A flat set of FieldConditions
- `ComplexConditionSet` - An infinitely deep set of FieldConditions and NestedConditions

### Defining your conditions

First, you will want to instantiate your condition set. If you only want a flat set of conditions
that cannot be nested, then use a `SimpleConditionSet`. Otherwise, use a `ComplexConditionSet`.

Next, you can pass your conditions to the condition set:

```php
use StellarWP\FieldConditions\ComplexConditionSet;
use StellarWP\FieldConditions\FieldCondition;
use StellarWP\FieldConditions\NestedCondition;
use StellarWP\FieldConditions\SimpleConditionSet;

$simpleSet = new SimpleConditionSet(); // you can pass conditions here as well

$simpleSet
    ->where('name', '=', 'John')
    ->and('age', '>', 18)
    ->or('age', '<', 5);

// Logically: name = 'John' AND age > 18 OR (name = 'Jane' AND age > 21)
$complexSet = new ComplexConditionSet();
$complexSet
    ->where('name', '=', 'John')
    ->and('age', '>', 18)
    ->or(function(NestedCondition $condition) {
        $condition
            ->where('name', '=', 'Jane')
            ->and('age', '>', 21);
    });
```

Condition instances can also be passed to the `where`, `and`, and `or` methods. Note that the
logical operator in the condition will be overwritten by the method used.

```php
$conditionSet = new SimpleConditionSet();

$nestedCondition = new NestedCondition();

$conditionSet
    ->where(new FieldCondition('name', '=', 'John'));
    ->and($nestedCondition);
```

It's also possible to append conditions to an existing condition set:

```php
$conditionSet = new SimpleConditionSet();
$conditionSet->append(
    new FieldCondition('name', '=', 'John'),
    new FieldCondition('age', '>', 18)
);
```

### Checking values against conditions

Once you have your condition set, you will want to pass values to the condition set to check whether
the given data passes the set of conditions.

```php
$data = [
    'name' => 'John',
    'age' => 19,
];

$simpleSet
    ->where('name', '=', 'John')
    ->and('age', '>', 18);

$conditionSet->passes($data); // true
$conditionSet->fails($data); // false
```

### A note about logical operators (and/or) in conditions

One thing that may be confusing is how logical operations work in conditions. Consider the
following:

```php
$conditionSet = new SimpleConditionSet(
    new FieldCondition('name', '=', 'John'),
    new FieldCondition('age', '>', 18, 'or')
);
```

Logically, this reads as "name equals John OR age is greater than 18". What makes this feel
strange is that the logical operator appears at the end of the second condition. This makes it feel
like the OR is being applied to the end of the second condition. In reality, the OR is being applied
to the start of the condition.

The logical operator is applied to the start of the condition, as the logical operation applies to
the current condition, not the next. This is why the typical way to write the sets is using the
`where()`, `and()`, and `or()` methods, so it feels more natural.

Lastly, the default logical operator is AND, so it is only necessary to specify the logical operator
when using OR.
