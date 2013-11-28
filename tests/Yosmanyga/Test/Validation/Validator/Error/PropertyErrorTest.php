<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\PropertyError;

class PropertyErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::__construct
     */
    public function testConstruct()
    {
        $text = 'text';
        $path = 'path';
        $error = new PropertyError($text, $path);
        $this->assertAttributeEquals($text, 'text', $error);
        $this->assertAttributeEquals($path, 'path', $error);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::__toString
     */
    public function testToString()
    {
        $text = 'text';
        $error = new PropertyError($text);
        ob_start();
        echo $error;
        $result = ob_get_clean();
        $this->assertEquals($result, $text);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::getText
     */
    public function testGetText()
    {
        $text = 'text';
        $error = new PropertyError($text);
        $this->assertEquals($text, $error->getText());
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::setPath
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::getPath
     */
    public function testSetGetPath()
    {
        $error = new PropertyError('', '');
        $error->setPath('path');
        $this->assertEquals('path', $error->getPath());
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\PropertyError::prependPath
     */
    public function testPrependPath()
    {
        $error = new PropertyError('', 'p1');
        $error->prependPath('p2');
        $this->assertEquals('p2.p1', $error->getPath());
    }
}
