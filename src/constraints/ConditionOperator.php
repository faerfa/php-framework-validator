<?php
declare(strict_types=1);

namespace framework\validator\constraints;

enum ConditionOperator: string
{
    case EQUAL = '==';
    case NOT_EQUAL = '!=';
}