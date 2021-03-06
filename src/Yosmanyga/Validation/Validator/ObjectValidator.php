<?php

namespace Yosmanyga\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\Error\PropertyError;

class ObjectValidator implements ValidatorInterface
{
    /**
     * @var \Yosmanyga\Validation\Validator\PropertyValidatorInterface[]
     */
    private $validators;

    /**
     * @var array
     */
    private $options;

    /**
     * @param \Yosmanyga\Validation\Validator\PropertyValidatorInterface[] $validators
     * @param array                                                        $options
     */
    public function __construct($validators = [], $options = [])
    {
        $this->validators = $validators;
        $this->options = array_replace_recursive([
            'message' => 'Value must be an object',
        ], $options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        if (!is_object($value)) {
            return [new Error($this->options['message'])];
        }

        $this->validators = $this->fixValidators($this->validators);

        $errors = [];

        foreach ($this->validators as $property => $validators) {
            /** @var \Yosmanyga\Validation\Validator\PropertyValidatorInterface[] $validators */
            foreach ($validators as $validator) {
                $validatorErrors = $validator->validate($value, $property);
                foreach ($validatorErrors as $key => $validatorError) {
                    if ($validatorError instanceof Error) {
                        $validatorErrors[$key] = new PropertyError($validatorError->getText(), $property);
                    } elseif ($validatorError instanceof PropertyError) {
                        $validatorError->prependPath($property);
                    }
                }

                if ($validatorErrors) {
                    $errors = array_merge($errors, $validatorErrors);
                }
            }
        }

        return $errors;
    }

    /**
     * @return \Yosmanyga\Validation\Validator\PropertyValidatorInterface[]
     */
    public function getValidators()
    {
        return $this->validators;
    }

    /**
     * @param \Yosmanyga\Validation\Validator\PropertyValidatorInterface[] $validators
     */
    public function setValidators($validators)
    {
        $this->validators = $validators;
    }

    /**
     * @param array $objectValidators
     *
     * @return \Yosmanyga\Validation\Validator\PropertyValidatorInterface[]
     */
    private function fixValidators($objectValidators)
    {
        $fixedValidators = [];
        foreach ($objectValidators as $property => $propertyValidators) {
            if (!is_array($propertyValidators)) {
                $propertyValidators = [$propertyValidators];
            }

            $fixedPropertyValidators = [];
            foreach ($propertyValidators as $propertyValidator) {
                if (!$propertyValidator instanceof PropertyValidatorInterface) {
                    $fixedPropertyValidators[] = new PropertyValidator([$propertyValidator]);
                } else {
                    $fixedPropertyValidators[] = $propertyValidator;
                }
            }

            $fixedValidators[$property] = $fixedPropertyValidators;
        }

        return $fixedValidators;
    }
}
