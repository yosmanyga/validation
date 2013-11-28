<?php

namespace Yosmanyga\Test\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Validator\Error\Error::__construct
     */
    public function testConstruct()
    {
        $text = 'text';
        $error = new Error($text);
        $this->assertAttributeEquals($text, 'text', $error);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\Error::__toString
     */
    public function testToString()
    {
        $text = 'text';
        $error = new Error($text);
        ob_start();
        echo $error;
        $result = ob_get_clean();
        $this->assertEquals($result, $text);
    }

    /**
     * @covers Yosmanyga\Validation\Validator\Error\Error::getText
     */
    public function testGetText()
    {
        $text = 'text';
        $error = new Error($text);
        $this->assertEquals($text, $error->getText());
    }
}
