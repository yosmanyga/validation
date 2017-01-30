<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\ValueValidator;

class ValueValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ValueValidator::__construct
     */
    public function testConstruct()
    {
        // Default options

        $validator = new ValueValidator();
        $this->assertAttributeEquals(
            [
                'allowNull' => false,
                'type'      => null,
                'eq'        => null,
                'neq'       => null,
                'iq'        => null,
                'niq'       => null,
                'gt'        => null,
                'ge'        => null,
                'lt'        => null,
                'le'        => null,
                'in'        => null,
                'nin'       => null,
                'messages'  => [
                    'null' => "Value can't be null",
                    'type' => 'Value must be of type "%s"',
                    'eq'   => 'Value must be equal to "%s"',
                    'neq'  => 'Value must not be equal to "%s"',
                    'iq'   => 'Value must be identical to "%s"',
                    'niq'  => 'Value must not be identical to "%s"',
                    'gt'   => 'Value must be greater than "%s"',
                    'ge'   => 'Value must be greater or equal to "%s"',
                    'lt'   => 'Value must be lower than "%s"',
                    'le'   => 'Value must be lower or equal to "%s"',
                    'in'   => 'Value must be one of these values "%s"',
                    'nin'  => 'Value must not be one of these values "%s"',
                ],
            ],
            'options',
            $validator
        );

        // Custom options

        $validator = new ValueValidator([
            'allowNull' => false,
            'type'      => 'a_type',
            'eq'        => 1,
            'neq'       => 2,
            'iq'        => 3,
            'niq'       => 4,
            'gt'        => 5,
            'ge'        => 6,
            'lt'        => 7,
            'le'        => 8,
            'in'        => 9,
            'nin'       => 10,
            'messages'  => [
                'null' => 'Message 1',
                'type' => 'Message 2',
                'eq'   => 'Message 3',
                'neq'  => 'Message 4',
                'iq'   => 'Message 5',
                'niq'  => 'Message 6',
                'gt'   => 'Message 7',
                'ge'   => 'Message 8',
                'lt'   => 'Message 9',
                'le'   => 'Message 10',
                'in'   => 'Message 11',
                'nin'  => 'Message 12',
            ],
        ]);
        $this->assertAttributeEquals(
            [
                'allowNull' => false,
                'type'      => 'a_type',
                'eq'        => 1,
                'neq'       => 2,
                'iq'        => 3,
                'niq'       => 4,
                'gt'        => 5,
                'ge'        => 6,
                'lt'        => 7,
                'le'        => 8,
                'in'        => 9,
                'nin'       => 10,
                'messages'  => [
                    'null' => 'Message 1',
                    'type' => 'Message 2',
                    'eq'   => 'Message 3',
                    'neq'  => 'Message 4',
                    'iq'   => 'Message 5',
                    'niq'  => 'Message 6',
                    'gt'   => 'Message 7',
                    'ge'   => 'Message 8',
                    'lt'   => 'Message 9',
                    'le'   => 'Message 10',
                    'in'   => 'Message 11',
                    'nin'  => 'Message 12',
                ],
            ],
            'options',
            $validator
        );
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ValueValidator::validate
     * @covers Yosmanyga\Validation\Validator\ValueValidator::configureMessages
     */
    public function testValidate()
    {
        $validator = new ValueValidator(['allowNull' => false]);
        $errors = $validator->validate(null);
        $this->assertEquals([new Error("Value can't be null")], $errors);

        $validator = new ValueValidator(['allowNull' => true]);
        $errors = $validator->validate(null);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['type' => 'integer']);
        $errors = $validator->validate('bar');
        $this->assertEquals([new Error('Value must be of type "integer"')], $errors);

        $validator = new ValueValidator(['eq' => 'foo']);
        $errors = $validator->validate('bar');
        $this->assertEquals([new Error('Value must be equal to "foo"')], $errors);

        $validator = new ValueValidator(['eq' => 'foo']);
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['neq' => 'foo']);
        $errors = $validator->validate('foo');
        $this->assertEquals([new Error('Value must not be equal to "foo"')], $errors);

        $validator = new ValueValidator(['neq' => 'foo']);
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['iq' => 'foo']);
        $errors = $validator->validate('bar');
        $this->assertEquals([new Error('Value must be identical to "foo"')], $errors);

        $validator = new ValueValidator(['iq' => 'foo']);
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['niq' => 'foo']);
        $errors = $validator->validate('foo');
        $this->assertEquals([new Error('Value must not be identical to "foo"')], $errors);

        $validator = new ValueValidator(['niq' => 'foo']);
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['gt' => 2]);
        $errors = $validator->validate(2);
        $this->assertEquals([new Error('Value must be greater than "2"')], $errors);

        $validator = new ValueValidator(['gt' => 2]);
        $errors = $validator->validate(3);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['ge' => 2]);
        $errors = $validator->validate(1);
        $this->assertEquals([new Error('Value must be greater or equal to "2"')], $errors);

        $validator = new ValueValidator(['ge' => 2]);
        $errors = $validator->validate(2);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['lt' => 2]);
        $errors = $validator->validate(2);
        $this->assertEquals([new Error('Value must be lower than "2"')], $errors);

        $validator = new ValueValidator(['lt' => 2]);
        $errors = $validator->validate(1);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['le' => 2]);
        $errors = $validator->validate(3);
        $this->assertEquals([new Error('Value must be lower or equal to "2"')], $errors);

        $validator = new ValueValidator(['le' => 2]);
        $errors = $validator->validate(2);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['in' => ['foo']]);
        $errors = $validator->validate('bar');
        $this->assertEquals([new Error('Value must be one of these values "foo"')], $errors);

        $validator = new ValueValidator(['in' => ['foo']]);
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(['nin' => ['foo']]);
        $errors = $validator->validate('foo');
        $this->assertEquals([new Error('Value must not be one of these values "foo"')], $errors);

        $validator = new ValueValidator(['nin' => ['foo']]);
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);
    }
}
