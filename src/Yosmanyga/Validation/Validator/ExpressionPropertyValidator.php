<?php

namespace Yosmanyga\Validation\Validator;

use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ExpressionPropertyValidator implements PropertyValidatorInterface
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var \Yosmanyga\Validation\Validator\ExpressionValueValidator
     */
    private $expressionValueValidator;

    /**
     * @param string                                                      $expression
     * @param array                                                       $options
     * @param \Symfony\Component\PropertyAccess\PropertyAccessorInterface $propertyAccessor
     * @param \Yosmanyga\Validation\Validator\ExpressionValueValidator    $expressionValueValidator
     */
    public function __construct($expression, $options = array(), PropertyAccessorInterface $propertyAccessor = null, ExpressionValueValidator $expressionValueValidator = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: new PropertyAccessor();
        $this->expressionValueValidator = $expressionValueValidator ?: new ExpressionValueValidator($expression, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object, $property)
    {
        $this->expressionValueValidator->addVariable('this', $object);

        $value = $this->propertyAccessor->getValue($object, $property);

        return $this->expressionValueValidator->validate($value);
    }
}
