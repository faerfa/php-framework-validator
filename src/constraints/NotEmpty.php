<?php
declare(strict_types=1);

namespace framework\validator\constraints;

use Attribute;
use framework\validator\Constraint;
use framework\validator\ValidationException;

/**
 * NotEmpty 约束类
 *
 * 该类提供了一个检查值是否非空的约束。
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class NotEmpty implements Constraint
{
    /**
     * 如果验证失败，要显示的消息。
     *
     * @var string
     */
    private string $message;

    /**
     * NotEmpty 构造函数。
     *
     * @param string $message 如果验证失败，要显示的消息,其中的 {name} 会被替换为字段名。
     */
    public function __construct(string $message = "{name} must not be empty")
    {
        $this->message = $message;
    }

    public function validation(string $name, mixed $value): mixed
    {
        if (empty($value)) {
            throw new ValidationException(str_replace(["{name}"], [$name], $this->message));
        }
        return $value;
    }

}