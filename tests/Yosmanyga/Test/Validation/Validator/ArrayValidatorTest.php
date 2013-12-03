<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\Error\Error;
use Yosmanyga\Validation\Validator\Error\PropertyError;

class ArrayValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::__construct
     */
    public function testConstruct()
    {
        // Default options

        $validator = new ArrayValidator();
        $this->assertAttributeEquals(
            array(
                'allowNull' => true,
                'map' => null,
                'requiredKeys' => array(),
                'allowedKeys' => array(),
                'deniedKeys' => array(),
                'allowExtra' => true,
                'messages' => array(
                    'null' => "Value can't be null",
                    'type' => 'Value must be an array',
                    'map' => 'Values are invalid',
                    'requiredKeys' => 'These keys are required "%s"',
                    'deniedKeys' => 'These keys are denied "%s"',
                    'allowExtra' => 'Only these keys are allowed "%s"'

                )
            ),
            'options',
            $validator
        );

        // Custom options

        $validator = new ArrayValidator(array(
            'allowNull' => false,
            'map' => 'function',
            'requiredKeys' => array('key1'),
            'allowedKeys' => array('key2'),
            'deniedKeys' => array('key3'),
            'allowExtra' => false,
            'messages' => array(
                'null' => "Message 1",
                'type' => 'Message 2',
                'map' => 'Message 3',
                'requiredKeys' => 'Message 4',
                'deniedKeys' => 'Message 5',
                'allowExtra' => 'Message 6'
            )
        ));
        $this->assertAttributeEquals(
            array(
                'allowNull' => false,
                'map' => 'function',
                'requiredKeys' => array('key1'),
                'allowedKeys' => array('key2'),
                'deniedKeys' => array('key3'),
                'allowExtra' => false,
                'messages' => array(
                    'null' => "Message 1",
                    'type' => 'Message 2',
                    'map' => 'Message 3',
                    'requiredKeys' => 'Message 4',
                    'deniedKeys' => 'Message 5',
                    'allowExtra' => 'Message 6'
                )
            ),
            'options',
            $validator
        );
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::validate
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::configureMessages
     */
    public function testValidate()
    {
        // Allow null
        $validator = new ArrayValidator();
        $errors = $validator->validate(null);
        $this->assertEmpty($errors);

        // Don't allow null
        $validator = new ArrayValidator(array('allowNull' => false));
        $errors = $validator->validate(null);
        $this->assertEquals(array(new Error("Value can't be null")), $errors);

        // Not array
        $validator = new ArrayValidator();
        $errors = $validator->validate('foo');
        $this->assertEquals(array(new Error('Value must be an array')), $errors);

        // Map invalid
        $validator = new ArrayValidator(array('map' => function ($e) { if ('foo' != $e) return 'Error'; else return null; }));
        $errors = $validator->validate(array('foo', 'bar'));
        $this->assertEquals(array(new PropertyError('Error', 1)), $errors);

        // Map valid
        $validator = new ArrayValidator(array('map' => function ($e) { if ('foo' != $e) return 'Error'; else return null; }));
        $errors = $validator->validate(array('foo', 'foo'));
        $this->assertEmpty($errors);

        // Denied keys
        $validator = new ArrayValidator(array('deniedKeys' => array('foo')));
        $errors = $validator->validate(array('foo' => 'value', 'bar' => 'value'));
        $this->assertEquals(array(new Error('These keys are denied "foo"')), $errors);

        // No denied keys
        $validator = new ArrayValidator(array('deniedKeys' => array('foo')));
        $errors = $validator->validate(array('bar' => 'value'));
        $this->assertEmpty($errors);

        // Required keys
        $validator = new ArrayValidator(array('requiredKeys' => array('foo')));
        $errors = $validator->validate(array('bar' => 'value'));
        $this->assertEquals(array(new Error('These keys are required "foo"')), $errors);

        // No missing required keys
        $validator = new ArrayValidator(array('requiredKeys' => array('foo')));
        $errors = $validator->validate(array('foo' => 'value'));
        $this->assertEmpty($errors);

        // Extra keys
        $validator = new ArrayValidator(array('allowExtra' => false, 'allowedKeys' => array('foo')));
        $errors = $validator->validate(array('foo' => 'value', 'bar' => 'value'));
        $this->assertEquals(array(new Error('Only these keys are allowed "foo"')), $errors);

        // No extra keys
        $validator = new ArrayValidator(array('allowExtra' => false, 'allowedKeys' => array('foo')));
        $errors = $validator->validate(array('foo' => 'value', 'bar' => 'value'));
        $this->assertEquals(array(new Error('Only these keys are allowed "foo"')), $errors);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::validate
     * @expectedException \InvalidArgumentException
     */
    public function testValidateThrowsExceptionWhenGroupNotFound()
    {
        $validator = new ArrayValidator(array('map' => 'foo'));
        $validator->validate(array('foo', 'bar'));
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::validateMap
     */
    public function testValidateMap()
    {
        $v = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $validator = new ArrayValidator();
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('options');
        $p->setAccessible(true);
        $p->setValue($validator, array('map' => $v));
        $m = $r->getMethod('validateMap');
        $m->setAccessible(true);
        $m->invoke($validator, array());
        $this->assertEquals(array('map' => array($v, 'validate')), $p->getValue($validator));

        $array = array('foo1');
        $v = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $v
            ->expects($this->once())->method('validate')->with('foo1')
            ->will($this->returnValue(new Error('Error 2')));
        $validator = new ArrayValidator();
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('options');
        $p->setAccessible(true);
        $p->setValue($validator, array('map' => $v, 'messages' => array('map' => 'Error 1')));
        $m = $r->getMethod('validateMap');
        $m->setAccessible(true);
        $this->assertEquals(array(new PropertyError('Error 2', '0')), $m->invoke($validator, $array));

        $array = array('foo1');
        $v = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $v
            ->expects($this->once())->method('validate')->with('foo1')
            ->will($this->returnValue(new PropertyError('Error 2', 'path')));
        $validator = new ArrayValidator();
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('options');
        $p->setAccessible(true);
        $p->setValue($validator, array('map' => $v, 'messages' => array('map' => 'Error 1')));
        $m = $r->getMethod('validateMap');
        $m->setAccessible(true);
        $this->assertEquals(array(new PropertyError('Error 2', '0.path')), $m->invoke($validator, $array));

        $array = array('foo1');
        $v = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $v
            ->expects($this->once())->method('validate')->with('foo1')
            ->will($this->returnValue('Error 2'));
        $validator = new ArrayValidator();
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('options');
        $p->setAccessible(true);
        $p->setValue($validator, array('map' => $v, 'messages' => array('map' => 'Error 1')));
        $m = $r->getMethod('validateMap');
        $m->setAccessible(true);
        $this->assertEquals(array(new PropertyError('Error 2', '0')), $m->invoke($validator, $array));
    }

    /**
     * @covers Yosmanyga\Validation\Validator\ArrayValidator::validateMap
     * @expectedException \InvalidArgumentException
     */
    public function testValidateMapThrowsExceptionWhenMapIsNotCallable()
    {
        $validator = new ArrayValidator();
        $r = new \ReflectionClass($validator);
        $p = $r->getProperty('options');
        $p->setAccessible(true);
        $p->setValue($validator, array('map' => 'foo'));
        $m = $r->getMethod('validateMap');
        $m->setAccessible(true);
        $m->invoke($validator, array());
    }
}
