<?php
declare(strict_types=1);

namespace framework\validator\constraints;

use Attribute;
use framework\validator\Constraint;
use framework\validator\ValidationException;

/**
 * 用于验证一个值是否符合给定的正则表达式。
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
class Pattern implements Constraint
{
    /**
     * 正则表达式
     * @var string
     */
    private string $regexp;

    /**
     * 正则修饰符
     * @var string
     */
    private string $modifier;

    /**
     * 提示信息
     * @var string
     */
    private string $message;

    /**
     * Construct 正则表达式验证
     * @param string $regexp 正则表达式
     * @param PatternModifier[]|PatternModifier $modifiers 正则修饰符
     * @param string $message 提示信息
     */
    public function __construct(string $regexp, array|PatternModifier $modifiers = [], string $message = "{name} must match \"{regexp}\"")
    {
        $this->regexp = $regexp;
        if (is_array($modifiers)) {
            $this->modifier = implode(array_map(function (PatternModifier $modifier) {
                return $modifier->value;
            }, $modifiers));
        } else {
            $this->modifier = $modifiers->value;
        }


        $this->message = $message;
    }

    public function validation(string $name, mixed $value): mixed
    {

        $pattern = "/" . str_replace("/", "\/", $this->regexp) . "/" . $this->modifier;

        switch (gettype($value)) {
            case "array":
                foreach ($value as $subject) {
                    $match = preg_match($pattern, $subject);
                    if (!$match) {
                        throw new ValidationException(str_replace(["{name}", "{regexp}"], [$name, $this->regexp], $this->message));
                    }
                }
                break;
            default:
                $match = preg_match($pattern, $value);
                if (!$match) {
                    throw new ValidationException(str_replace(["{name}", "{regexp}"], [$name, $this->regexp], $this->message));
                }
                break;
        }

        return $value;
    }

}