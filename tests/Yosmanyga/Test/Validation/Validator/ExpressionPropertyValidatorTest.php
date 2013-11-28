<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\ExpressionPropertyValidator;

class ExpressionPropertyValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionPropertyValidator::__construct
     */
    public function testConstruct()
    {
        $expression = 'foo';
        $validator = new ExpressionPropertyValidator($expression);
        $this->assertAttributeEquals($expression, 'expression', $validator);
        $this->assertAttributeEquals(array('message' => 'This value is not valid'), 'options', $validator);
        $this->assertAttributeInstanceOf('Symfony\Component\PropertyAccess\PropertyAccessor', 'propertyAccessor', $validator);
        $this->assertAttributeInstanceOf('Yosmanyga\Validation\Validator\ExpressionValueValidator', 'expressionValueValidator', $validator);

        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessor');
        $expressionValueValidator = $this->getMock('Yosmanyga\Validation\Validator\ExpressionValueValidator');
        $options = array('message' => 'foo');
        $validator = new ExpressionPropertyValidator($expression, $options, $propertyAccessor, $expressionValueValidator);
        $this->assertAttributeEquals($options, 'options', $validator);
        $this->assertAttributeEquals($propertyAccessor, 'propertyAccessor', $validator);
        $this->assertAttributeEquals($expressionValueValidator, 'expressionValueValidator', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionPropertyValidator::validate
     */
    public function testValidate()
    {
        $expression = 'foo';
        $object = (object) array('foo' => 'bar');
        $property = 'foo';
        $value = 'bar';
        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessor');
        $propertyAccessor
            ->expects($this->once())->method('getValue')
            ->with($object, $property)
            ->will($this->returnValue($value));
        $expressionValueValidator = $this->getMock('Yosmanyga\Validation\Validator\ExpressionValueValidator');
        $expressionValueValidator
            ->expects($this->once())->method('setExpression')
            ->with($expression);
        $expressionValueValidator
            ->expects($this->once())->method('addVariable')
            ->with('this', $object);
        $expressionValueValidator
            ->expects($this->once())->method('validate')
            ->with($value);
        /** @var \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor */
        /** @var \Yosmanyga\Validation\Validator\ExpressionValueValidator $expressionValueValidator */
        $validator = new ExpressionPropertyValidator($expression, array(), $propertyAccessor, $expressionValueValidator);
        $validator->validate($object, $property);
    }
}
