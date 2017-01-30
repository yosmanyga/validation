<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\Error\PropertyError;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\PropertyValidator;

class ObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::__construct
     */
    public function testConstruct()
    {
        $validator = new ObjectValidator();
        $this->assertAttributeEquals([], 'validators', $validator);
        $this->assertAttributeEquals(['message' => 'Value must be an object'], 'options', $validator);

        $validators = ['foo'];
        $options = ['message' => 'foo'];
        $validator = new ObjectValidator($validators, $options);
        $this->assertAttributeEquals($validators, 'validators', $validator);
        $this->assertAttributeEquals($options, 'options', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::validate
     */
    public function testValidateOnNoObject()
    {
        $validator = new ObjectValidator();
        $errors = $validator->validate('foo');
        $this->assertEquals([new Error('Value must be an object')], $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::validate
     */
    public function testValidate()
    {
        $object = (object) ['foo' => 'bar'];
        $validator = $this->getMock('Yosmanyga\Validation\Validator\PropertyValidatorInterface');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($object, 'foo')
            ->will($this->returnValue([new Error('message')]));
        $validators = ['foo' => $validator];
        $validator = new ObjectValidator($validators);
        $errors = $validator->validate($object);
        $this->assertEquals([new PropertyError('message', 'foo')], $errors);

        $object = (object) ['foo' => 'bar'];
        $validator = $this->getMock('Yosmanyga\Validation\Validator\PropertyValidatorInterface');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($object, 'foo')
            ->will($this->returnValue([new PropertyError('message', 'path')]));
        $validators = ['foo' => $validator];
        $validator = new ObjectValidator($validators);
        $errors = $validator->validate($object);
        $this->assertEquals([new PropertyError('message', 'foo.path')], $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::setValidators
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::getValidators
     */
    public function testGetSetValidators()
    {
        $validator = new ObjectValidator();
        $validator->setValidators(['foo']);
        $this->assertEquals(['foo'], $validator->getValidators());
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::fixValidators
     */
    public function testFixValidators()
    {
        $r = new \ReflectionClass('Yosmanyga\Validation\Validator\ObjectValidator');
        $m = $r->getMethod('fixValidators');
        $m->setAccessible(true);

        $validators = ['property1' => 'foo'];
        $validator = new ObjectValidator();
        $this->assertEquals(['property1' => [new PropertyValidator(['foo'])]], $m->invoke($validator, $validators));

        $validators = ['property1' => new PropertyValidator(['foo'])];
        $validator = new ObjectValidator();
        $this->assertEquals(['property1' => [new PropertyValidator(['foo'])]], $m->invoke($validator, $validators));
    }
}
