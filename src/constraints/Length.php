<?php
declare(strict_types=1);

namespace framework\validator\constraints;

use Attribute;
use framework\validator\Constraint;
use framework\validator\ValidationException;

/**
 * 长度限制类
 *
 * 此类用于验证字符串的长度。可以作为属性和参数的注解使用。
 *
 * @attribute Length
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class Length implements Constraint
{
    public int $min;

    public int $max;

    public string $message;

    /**
     * 长度限制类的构造函数
     *
     * @param int $min 字符串的最小长度，默认为0。
     * @param int $max 字符串的最大长度，默认为PHP_INT_MAX。
     * @param string $message 当验证失败时显示的错误信息。
     */
    public function __construct(int $min = 0, int $max = PHP_INT_MAX, string $message = "{name} length must be between {min} and {max}")
    {
        $this->min = $min;
        $this->max = $max;
        $this->message = $message;
    }

    public function validation(string $name, mixed $value): mixed
    {
        $length = mb_strlen((string)$value);

        if ($length > $this->max || $length < $this->min) {
            throw new ValidationException(str_replace(["{name}", "{min}", "{max}"], [$name, $this->min, $this->max], $this->message));
        }

        return $value;
    }
}