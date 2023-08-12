<?php
declare(strict_types=1);

namespace framework\validator\constraints;

use framework\validator\Constraint;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Condition implements Constraint
{
    public string $name;

    public ConditionOperator $operator;

    public mixed $value;

    public function __construct(string $name, ConditionOperator $operator, mixed $value)
    {
        $this->name = $name;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function validation(string $name, mixed $value): bool
    {
        return match ($this->operator) {
            ConditionOperator::EQUAL => $this->value == $value,
            ConditionOperator::NOT_EQUAL => $this->value != $value
        };
    }
}