<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\PropertyError;
use Yosmanyga\Validation\Validator\GroupObjectValidator;

class GroupObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\GroupObjectValidator::__construct
     */
    public function testConstruct()
    {
        $validators = ['foo'];
        $validator = new GroupObjectValidator($validators);
        $this->assertAttributeEquals($validators, 'validators', $validator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\GroupObjectValidator::validate
     */
    public function testValidate()
    {
        $object = (object) ['foo' => 'bar'];
        $validator1 = $this->getMock('Yosmanyga\Validation\Validator\ObjectValidator');
        $validator1
            ->expects($this->once())->method('validate')->with($object)
            ->will($this->returnValue([new PropertyError('')]));
        $validator2 = $this->getMock('Yosmanyga\Validation\Validator\ObjectValidator');
        $validator2
            ->expects($this->once())->method('validate')->with($object)
            ->will($this->returnValue([new PropertyError('')]));
        $validator3 = $this->getMock('Yosmanyga\Validation\Validator\ObjectValidator');
        $validators = ['Group1' => $validator1, 'Group2' => $validator2, 'Group3' => $validator3];
        $validator = new GroupObjectValidator($validators);
        $errors = $validator->validate($object, ['Group1', 'Group2']);
        $this->assertEquals([new PropertyError(''), new PropertyError('')], $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\GroupObjectValidator::validate
     */
    public function testValidateDefaultGroup()
    {
        $object = (object) ['foo' => 'bar'];
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ObjectValidator');
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($object)
            ->will($this->returnValue([new PropertyError('')]));
        $validators = ['Default' => $validator];
        $validator = new GroupObjectValidator($validators);
        $errors = $validator->validate($object);
        $this->assertEquals([new PropertyError('')], $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\GroupObjectValidator::validate
     * @expectedException \InvalidArgumentException
     */
    public function testValidateThrowsExceptionWhenGroupNotFound()
    {
        $validators = ['foo'];
        $validator = new GroupObjectValidator($validators);
        $validator->validate('foo');
    }

    /**
     * @covers Yosmanyga\Validation\Validator\GroupObjectValidator::fixGroups
     */
    public function testFixGroups()
    {
        $validator = new GroupObjectValidator(null);
        $r = new \ReflectionClass($validator);
        $m = $r->getMethod('fixGroups');
        $m->setAccessible(true);
        $this->assertEquals(['Default'], $m->invoke($validator, []));
    }
}
