<?php
declare(strict_types=1);

namespace framework\validator\constraints;

use Attribute;

/**
 * Email 校验类，用于验证一个值是否符合电子邮件地址的格式。
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Email extends Pattern
{
    /**
     * Email 校验
     * @param string $message 如果验证失败，要显示的消息,其中的 {name} 会被替换为字段名。
     */
    public function __construct(string $message = "{name} not a well-formed email address")
    {
        parent::__construct("^[A-Z0-9+_.-]+@[A-Z0-9.-]+$", PatternModifier::PCRE_CASELESS, $message);
    }
}