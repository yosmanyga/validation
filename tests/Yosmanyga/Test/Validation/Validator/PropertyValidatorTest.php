<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\PropertyValidator;

class PropertyValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\PropertyValidator::__construct
     */
    public function testConstruct()
    {
        $validator = new PropertyValidator();
        $this->assertAttributeEquals(array(), 'validators', $validator);
        $this->assertAttributeInstanceOf('Symfony\Component\PropertyAccess\PropertyAccessor', 'propertyAccessor', $validator);

        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessor');
        $validators = array('foo');
        $validator = new PropertyValidator($validators, $propertyAccessor);
        $this->assertAttributeEquals($validators, 'validators', $validator);
        $this->assertAttributeEquals($propertyAccessor, 'propertyAccessor', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\PropertyValidator::validate
     */
    public function testValidate()
    {
        $propertyAccessor = $this->getMock('Symfony\Component\PropertyAccess\PropertyAccessor');
        $propertyAccessor->expects($this->once())->method('getValue')->will($this->returnValue('bar'));
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $validator->expects($this->once())->method('validate')->with('bar')->will($this->returnValue(array('error1')));
        $validators = array($validator);
        /** @var \Symfony\Component\PropertyAccess\PropertyAccessor $propertyAccessor */
        $validator = new PropertyValidator($validators, $propertyAccessor);
        $errors = $validator->validate((object) array('foo' => 'bar'), 'foo');
        $this->assertEquals(array('error1'), $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\PropertyValidator::fixValidators
     */
    public function testFixValidators()
    {
        $r = new \ReflectionClass('Yosmanyga\Validation\Validator\PropertyValidator');
        $m = $r->getMethod('fixValidators');
        $m->setAccessible(true);

        $validators = 'foo';
        $validator = new PropertyValidator();
        $this->assertEquals(array($validators), $m->invoke($validator, $validators));
    }
}
