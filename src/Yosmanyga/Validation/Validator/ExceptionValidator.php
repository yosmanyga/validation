<?php

namespace Yosmanyga\Validation\Validator;

class ExceptionValidator implements ValidatorInterface
{
    /**
     * @var \Yosmanyga\Validation\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @param \Yosmanyga\Validation\Validator\ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        $errors = $this->validator->validate($value);

        if (!$errors) {
            return array();
        }

        throw new \RuntimeException(sprintf("Invalid value %s, got this error: %s", print_r($value, true), implode('. ', $errors)));
    }
}
