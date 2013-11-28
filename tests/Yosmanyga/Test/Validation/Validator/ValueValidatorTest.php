<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\ValueValidator;
use Yosmanyga\Validation\Validator\Error\Error;

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
            array(
                'allowNull' => true,
                'type' => null,
                'eq' => null,
                'neq' => null,
                'iq' => null,
                'niq' => null,
                'gt' => null,
                'ge' => null,
                'lt' => null,
                'le' => null,
                'in' => null,
                'nin' => null,
                'messages' => array(
                    'null' => "Value can't be null",
                    'type' => 'Value must be of type "%s"',
                    'eq' => 'Value must be equal to "%s"',
                    'neq' => 'Value must not be equal to "%s"',
                    'iq' => 'Value must be identical to "%s"',
                    'niq' => 'Value must not be identical to "%s"',
                    'gt' => 'Value must be greater than "%s"',
                    'ge' => 'Value must be greater or equal to "%s"',
                    'lt' => 'Value must be lower than "%s"',
                    'le' => 'Value must be lower or equal to "%s"',
                    'in' => 'Value must be one of these values "%s"',
                    'nin' => 'Value must not be one of these values "%s"',
                )
            ),
            'options',
            $validator
        );

        // Custom options

        $validator = new ValueValidator(array(
            'allowNull' => false,
            'type' => 'a_type',
            'eq' => 1,
            'neq' => 2,
            'iq' => 3,
            'niq' => 4,
            'gt' => 5,
            'ge' => 6,
            'lt' => 7,
            'le' => 8,
            'in' => 9,
            'nin' => 10,
            'messages' => array(
                'null' => 'Message 1',
                'type' => 'Message 2',
                'eq' => 'Message 3',
                'neq' => 'Message 4',
                'iq' => 'Message 5',
                'niq' => 'Message 6',
                'gt' => 'Message 7',
                'ge' => 'Message 8',
                'lt' => 'Message 9',
                'le' => 'Message 10',
                'in' => 'Message 11',
                'nin' => 'Message 12',
            )
        ));
        $this->assertAttributeEquals(
            array(
                'allowNull' => false,
                'type' => 'a_type',
                'eq' => 1,
                'neq' => 2,
                'iq' => 3,
                'niq' => 4,
                'gt' => 5,
                'ge' => 6,
                'lt' => 7,
                'le' => 8,
                'in' => 9,
                'nin' => 10,
                'messages' => array(
                    'null' => 'Message 1',
                    'type' => 'Message 2',
                    'eq' => 'Message 3',
                    'neq' => 'Message 4',
                    'iq' => 'Message 5',
                    'niq' => 'Message 6',
                    'gt' => 'Message 7',
                    'ge' => 'Message 8',
                    'lt' => 'Message 9',
                    'le' => 'Message 10',
                    'in' => 'Message 11',
                    'nin' => 'Message 12',
                )
            ),
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
        $validator = new ValueValidator(array('allowNull' => false));
        $errors = $validator->validate(null);
        $this->assertEquals(array(new Error("Value can't be null")), $errors);

        $validator = new ValueValidator(array('allowNull' => true));
        $errors = $validator->validate(null);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('type' => 'integer'));
        $errors = $validator->validate('bar');
        $this->assertEquals(array(new Error('Value must be of type "integer"')), $errors);

        $validator = new ValueValidator(array('eq' => 'foo'));
        $errors = $validator->validate('bar');
        $this->assertEquals(array(new Error('Value must be equal to "foo"')), $errors);

        $validator = new ValueValidator(array('eq' => 'foo'));
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('neq' => 'foo'));
        $errors = $validator->validate('foo');
        $this->assertEquals(array(new Error('Value must not be equal to "foo"')), $errors);

        $validator = new ValueValidator(array('neq' => 'foo'));
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('iq' => 'foo'));
        $errors = $validator->validate('bar');
        $this->assertEquals(array(new Error('Value must be identical to "foo"')), $errors);

        $validator = new ValueValidator(array('iq' => 'foo'));
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('niq' => 'foo'));
        $errors = $validator->validate('foo');
        $this->assertEquals(array(new Error('Value must not be identical to "foo"')), $errors);

        $validator = new ValueValidator(array('niq' => 'foo'));
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('gt' => 2));
        $errors = $validator->validate(2);
        $this->assertEquals(array(new Error('Value must be greater than "2"')), $errors);

        $validator = new ValueValidator(array('gt' => 2));
        $errors = $validator->validate(3);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('ge' => 2));
        $errors = $validator->validate(1);
        $this->assertEquals(array(new Error('Value must be greater or equal to "2"')), $errors);

        $validator = new ValueValidator(array('ge' => 2));
        $errors = $validator->validate(2);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('lt' => 2));
        $errors = $validator->validate(2);
        $this->assertEquals(array(new Error('Value must be lower than "2"')), $errors);

        $validator = new ValueValidator(array('lt' => 2));
        $errors = $validator->validate(1);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('le' => 2));
        $errors = $validator->validate(3);
        $this->assertEquals(array(new Error('Value must be lower or equal to "2"')), $errors);

        $validator = new ValueValidator(array('le' => 2));
        $errors = $validator->validate(2);
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('in' => array('foo')));
        $errors = $validator->validate('bar');
        $this->assertEquals(array(new Error('Value must be one of these values "foo"')), $errors);

        $validator = new ValueValidator(array('in' => array('foo')));
        $errors = $validator->validate('foo');
        $this->assertEmpty($errors);

        $validator = new ValueValidator(array('nin' => array('foo')));
        $errors = $validator->validate('foo');
        $this->assertEquals(array(new Error('Value must not be one of these values "foo"')), $errors);

        $validator = new ValueValidator(array('nin' => array('foo')));
        $errors = $validator->validate('bar');
        $this->assertEmpty($errors);
    }
}
