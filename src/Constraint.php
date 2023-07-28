<?php
declare(strict_types=1);

namespace framework\validator;

/**
 * 接口 Constraint
 *
 * 这个接口定义了一个用于验证值的约束。
 * 实现这个接口的类应当在它们的 validation 方法中提供具体的验证规则。
 */
interface Constraint
{
    /**
     * 基于特定规则验证给定的值。
     *
     * 这个方法需要接收一个名字和一个待验证的值，然后返回一个经过验证的值。如果提供的值不满足特定的规则，
     * 它将抛出一个 ValidationException 异常。方法的具体实现取决于实现此接口的具体类，可能会有不同的验证规则。
     *
     * @param string $name 用于在验证消息中引用的名称，可以是任意字符串，通常是待验证值的字段名或变量名。
     * @param mixed $value 需要被验证的值，可以是任意类型，具体取决于实现此接口的具体类的验证规则。
     * @return mixed 经过验证的值，如果值满足特定的规则，则原样返回；如果不满足，可能会抛出异常或进行其他处理，具体取决于实现此接口的具体类。
     * @throws ValidationException 如果提供的值无效或不满足特定的规则，则可能会抛出该异常。
     */
    public function validation(string $name, mixed $value): mixed;

}