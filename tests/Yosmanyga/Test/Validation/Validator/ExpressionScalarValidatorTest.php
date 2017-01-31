<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\ExpressionScalarValidator;

class ExpressionScalarValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionScalarValidator::__construct
     */
    public function testConstruct()
    {
        $expression = 'foo';
        $validator = new ExpressionScalarValidator($expression);
        $this->assertAttributeEquals($expression, 'expression', $validator);
        $this->assertAttributeEquals(['message' => 'This value is not valid'], 'options', $validator);

        $options = ['message' => 'foo'];
        $validator = new ExpressionScalarValidator($expression, $options);
        $this->assertAttributeEquals($options, 'options', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionScalarValidator::validate
     */
    public function testValidate()
    {
        $expression = 'foo';
        $value = 'bar';
        $expressionLanguage = $this->getMock('Symfony\Component\ExpressionLanguage\ExpressionLanguage');
        $expressionLanguage
            ->expects($this->once())->method('evaluate')
            ->with($expression, ['value' => $value])
            ->will($this->returnValue(false));
        /** @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage */
        $validator = new ExpressionScalarValidator($expression, [], $expressionLanguage);
        $errors = $validator->validate($value);
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('variables');
        $p->setAccessible(true);
        $this->assertEquals(['value' => $value], $p->getValue($validator));
        $this->assertEquals([new Error('This value is not valid')], $errors);
//
//        /** @var \PHPUnit_Framework_MockObject_MockObject $expressionLanguage */
//        $expressionLanguage = $this->getMock('Symfony\Component\ExpressionLanguage\ExpressionLanguage');
//        $expressionLanguage
//            ->expects($this->once())->method('evaluate')
//            ->with($expression, array('value' => $value))
//            ->will($this->returnValue(true));
//        /** @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage */
//        $validator = new ExpressionScalarValidator($expression, array(), $expressionLanguage);
//        $errors = $validator->validate($value);
//        $this->assertEquals(array(), $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExpressionScalarValidator::addVariable
     */
    public function testAddVariable()
    {
        $validator = new ExpressionScalarValidator();
        $validator->addVariable('foo', 'bar');
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('variables');
        $p->setAccessible(true);
        $this->assertEquals(['foo' => 'bar'], $p->getValue($validator));
    }
}
