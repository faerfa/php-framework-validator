<?php
declare(strict_types=1);

namespace framework\validator;

use framework\validator\constraints\NotEmpty;
use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionObject;
use ReflectionParameter;

/**
 * 注解映射校验数据
 */
class Validation
{

    /**
     * 对给定的对象进行验证
     *
     * @param object $object 需要被验证的对象
     */
    public static function object(object $object): object
    {
        $reflectionObject = new ReflectionObject($object);

        foreach ($reflectionObject->getProperties() as $reflectionProperty) {

            if (!$reflectionProperty->hasType()) continue;
            $name = $reflectionProperty->getName();
            $type = $reflectionProperty->getType();

            if (!$type instanceof ReflectionNamedType) continue;

            if ($reflectionProperty->isInitialized($object)) {

                $value = $reflectionProperty->getValue($object);

                if ($type->isBuiltin()) {
                    foreach ($reflectionProperty->getAttributes() as $attribute) {
                        $reflectionProperty->setValue($object, self::validation($attribute, $name, $value));
                    }
                    if ($type->getName() == "array") {
                        foreach ($value as $item) {
                            if (is_object($item)) {
                                self::object($item);
                            }
                        }
                    }
                } else {
                    self::object($value);
                }

            } else {

                if (empty($notEmpty = $reflectionProperty->getAttributes(NotEmpty::class))) continue;
                self::validation(current($notEmpty), $name, "");

            }

        }

        return $object;

    }

    /**
     * 验证给定的参数。
     *
     * 遍历参数的所有属性，对每个属性进行验证。
     *
     * @param ReflectionParameter $parameter 需要验证的参数
     * @param mixed $value 参数的值
     * @return mixed 验证后的值
     */
    public static function parameter(ReflectionParameter $parameter, mixed $value): mixed
    {
        foreach ($parameter->getAttributes() as $attribute) {
            $value = self::validation($attribute, $parameter->name, $value);
        }
        return $value;
    }

    /**
     * 对给定的 ReflectionAttribute 进行验证
     *
     * @param ReflectionAttribute $attribute 需要被验证的 ReflectionAttribute
     * @param string $name 字段名或变量名，将被用于替换错误消息中的 {name}
     * @param mixed $value 验证的值
     * @return mixed 验证后的值
     * @throws ValidationException 如果验证失败
     */
    private static function validation(ReflectionAttribute $attribute, string $name, mixed $value): mixed
    {
        $constraint = $attribute->newInstance();

        if ($constraint instanceof Constraint) {
            return $constraint->validation($name, $value);
        }

        return $value;
    }

}