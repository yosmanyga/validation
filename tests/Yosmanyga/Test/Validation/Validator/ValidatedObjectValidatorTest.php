<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\ValidatedObjectValidator;

class ValidatedObjectValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ValidatedObjectValidator::validate
     */
    public function testValidate()
    {
        $objectValidator = $this->getMock('Yosmanyga\Validation\Validator\ObjectValidator');
        $validatedObject = $this->getMock('Yosmanyga\Validation\Validator\ValidatedInterface');
        $validatedObject
            ->expects($this->once())
            ->method('createValidator')
            ->will($this->returnValue($objectValidator));
        $validator = new ValidatedObjectValidator();
        $validator->validate($validatedObject);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ValidatedObjectValidator::validate
     * @expectedException \InvalidArgumentException
     */
    public function testValidateThrowsExceptionWithInvalidValue()
    {
        $validator = new ValidatedObjectValidator();
        $validator->validate('foo');
    }
}
