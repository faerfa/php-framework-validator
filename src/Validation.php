<?php
declare(strict_types=1);

namespace framework\validator;

use framework\validator\constraints\Condition;
use framework\validator\constraints\NotEmpty;
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

                        $constraint = $attribute->newInstance();

                        if (!$constraint instanceof Constraint) continue;

                        if ($constraint instanceof Condition) {
                            if (!$reflectionObject->hasProperty($constraint->name)) continue 2;
                            if (!$reflectionObject->getProperty($constraint->name)->isInitialized($object)) continue 2;
                            if (!$constraint->validation($name, $reflectionObject->getProperty($constraint->name)->getValue($object))) continue 2;
                        }

                        $value = $constraint->validation($name, $value);
                        $reflectionProperty->setValue($object, $constraint->validation($name, $value));
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

                foreach ($reflectionProperty->getAttributes(NotEmpty::class) as $attribute) {
                    $constraint = $attribute->newInstance();
                    if (!$constraint instanceof Constraint) continue;
                    $constraint->validation($name, "");
                }

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
            $constraint = $attribute->newInstance();
            if (!$constraint instanceof Constraint) continue;
            $value = $constraint->validation($parameter->name, $value);
        }

        return $value;
    }
}