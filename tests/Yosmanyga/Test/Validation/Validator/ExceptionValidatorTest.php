<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class ExceptionValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ExceptionValidator::__construct
     */
    public function testConstruct()
    {
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $exceptionValidator */
        $exceptionValidator = new ExceptionValidator($validator);
        $this->assertAttributeEquals($validator, 'validator', $exceptionValidator);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExceptionValidator::validate
     */
    public function testValidate()
    {
        $value = 'foo';
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $validator
            ->expects($this->once())->method('validate')->with($value)
            ->will($this->returnValue([]));
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $validator */
        $exceptionValidator = new ExceptionValidator($validator);
        $exceptionValidator->validate($value);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ExceptionValidator::validate
     * @expectedException \RuntimeException
     */
    public function testValidateThrowsExceptionWhenValidatorReturnsErrors()
    {
        $value = 'foo';
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $validator
            ->expects($this->once())->method('validate')->with($value)
            ->will($this->returnValue([new Error('')]));
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $validator */
        $exceptionValidator = new ExceptionValidator($validator);
        $exceptionValidator->validate($value);
    }
}
