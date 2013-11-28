<?php

namespace Yosmanyga\Validation\Validator;

class ValidatedObjectValidator implements ValidatorInterface
{
    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        if (!$value instanceof ValidatedInterface) {
            throw new \InvalidArgumentException("Object must be instance of ValidatedInterface");
        }

        $validator = $value->createValidator();

        return $validator->validate($value);

    }
}
