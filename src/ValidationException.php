<?php
declare(strict_types=1);

namespace framework\validator;

/**
 * 验证异常
 */
class ValidationException extends \ValueError
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}