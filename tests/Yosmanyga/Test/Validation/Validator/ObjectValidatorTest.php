<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\PropertyValidator;
use Yosmanyga\Validation\Validator\Error\PropertyError;

class ObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::__construct
     */
    public function testConstruct()
    {
        $validator = new ObjectValidator();
        $this->assertAttributeEquals(array(), 'validators', $validator);
        $this->assertAttributeEquals(array('message' => 'Value must be an object'), 'options', $validator);

        $validators = array('foo');
        $options = array('message' => 'foo');
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
        $this->assertEquals(array(new Error('Value must be an object')), $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::validate
     */
    public function testValidate()
    {
        $object = (object) array('foo' => 'bar');
        $validator = $this->getMock('Yosmanyga\Validation\Validator\PropertyValidatorInterface');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($object, 'foo')
            ->will($this->returnValue(array(new Error('message'))));
        $validators = array('foo' => $validator);
        $validator = new ObjectValidator($validators);
        $errors = $validator->validate($object);
        $this->assertEquals(array(new PropertyError('message', 'foo')), $errors);

        $object = (object) array('foo' => 'bar');
        $validator = $this->getMock('Yosmanyga\Validation\Validator\PropertyValidatorInterface');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($object, 'foo')
            ->will($this->returnValue(array(new PropertyError('message', 'path'))));
        $validators = array('foo' => $validator);
        $validator = new ObjectValidator($validators);
        $errors = $validator->validate($object);
        $this->assertEquals(array(new PropertyError('message', 'foo.path')), $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ObjectValidator::fixValidators
     */
    public function testFixValidators()
    {
        $r = new \ReflectionClass('Yosmanyga\Validation\Validator\ObjectValidator');
        $m = $r->getMethod('fixValidators');
        $m->setAccessible(true);

        $validators = array('property1' => 'foo');
        $validator = new ObjectValidator();
        $this->assertEquals(array('property1' => array(new PropertyValidator(array('foo')))), $m->invoke($validator, $validators));

        $validators = array('property1' => new PropertyValidator(array('foo')));
        $validator = new ObjectValidator();
        $this->assertEquals(array('property1' => array(new PropertyValidator(array('foo')))), $m->invoke($validator, $validators));
    }
}
