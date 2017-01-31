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
        $validator = new ExpressionPropertyValidator('foo');
        $this->assertAttributeInstanceOf('Symfony\Component\PropertyAccess\PropertyAccessor', 'propertyAccessor', $validator);
        $this->assertAttributeInstanceOf('Yosmanyga\Validation\Validator\ExpressionScalarValidator', 'expressionScalarValidator', $validator);

        $expression = 'foo';
        $options = [];
        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessorInterface');
        $expressionScalarValidator = $this->getMock('Yosmanyga\Validation\Validator\ExpressionScalarValidator');
        $validator = new ExpressionPropertyValidator($expression, $options, $propertyAccessor, $expressionScalarValidator);
        $this->assertAttributeEquals($propertyAccessor, 'propertyAccessor', $validator);
        $this->assertAttributeEquals($expressionScalarValidator, 'expressionScalarValidator', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionPropertyValidator::validate
     */
    public function testValidate()
    {
        $expression = 'foo';
        $object = (object) ['foo' => 'bar'];
        $property = 'foo';
        $value = 'bar';
        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessor');
        $propertyAccessor
            ->expects($this->once())->method('getValue')
            ->with($object, $property)
            ->will($this->returnValue($value));
        $expressionScalarValidator = $this->getMock('Yosmanyga\Validation\Validator\ExpressionScalarValidator');
        $expressionScalarValidator
            ->expects($this->once())->method('addVariable')
            ->with('this', $object);
        $expressionScalarValidator
            ->expects($this->once())->method('validate')
            ->with($value);
        /** @var \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor */
        /** @var \Yosmanyga\Validation\Validator\ExpressionScalarValidator $expressionScalarValidator */
        $validator = new ExpressionPropertyValidator($expression, [], $propertyAccessor, $expressionScalarValidator);
        $validator->validate($object, $property);
    }
}
