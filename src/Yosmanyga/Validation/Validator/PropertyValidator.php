<?php

namespace Yosmanyga\Validation\Validator;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyValidator implements PropertyValidatorInterface
{
    /**
     * @var \Yosmanyga\Validation\Validator\ValidatorInterface[]
     */
    private $validators;

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @param \Yosmanyga\Validation\Validator\ValidatorInterface|\Yosmanyga\Validation\Validator\ValidatorInterface[] $validators
     * @param PropertyAccessorInterface                                                                               $propertyAccessor
     */
    public function __construct($validators = array(), PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->validators = $validators;
        $this->propertyAccessor = $propertyAccessor ?: new PropertyAccessor();
    }

    /**
     * @inheritdoc
     */
    public function validate($object, $property)
    {
        $this->validators = $this->fixValidators($this->validators);

        $errors = array();

        $value = $this->propertyAccessor->getValue($object, $property);

        foreach ($this->validators as $validator) {
            $validatorErrors = $validator->validate($value);
            if ($validatorErrors) {
                $errors = array_merge($validatorErrors, $errors);
            }
        }

        return $errors;
    }

    /**
     * @param $validators
     * @return \Yosmanyga\Validation\Validator\ValidatorInterface[]
     */
    private function fixValidators($validators)
    {
        if (!is_array($validators)) {
            $validators = array($validators);
        }

        return $validators;
    }
}
