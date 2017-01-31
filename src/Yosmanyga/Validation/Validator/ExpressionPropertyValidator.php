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
     * @var \Yosmanyga\Validation\Validator\ExpressionScalarValidator
     */
    private $expressionScalarValidator;

    /**
     * @param string                                                      $expression
     * @param array                                                       $options
     * @param \Symfony\Component\PropertyAccess\PropertyAccessorInterface $propertyAccessor
     * @param \Yosmanyga\Validation\Validator\ExpressionScalarValidator   $expressionScalarValidator
     */
    public function __construct($expression, $options = [], PropertyAccessorInterface $propertyAccessor = null, ExpressionScalarValidator $expressionScalarValidator = null)
    {
        $this->propertyAccessor = $propertyAccessor ?: new PropertyAccessor();
        $this->expressionScalarValidator = $expressionScalarValidator ?: new ExpressionScalarValidator($expression, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object, $property)
    {
        $this->expressionScalarValidator->addVariable('this', $object);

        $value = $this->propertyAccessor->getValue($object, $property);

        return $this->expressionScalarValidator->validate($value);
    }
}
