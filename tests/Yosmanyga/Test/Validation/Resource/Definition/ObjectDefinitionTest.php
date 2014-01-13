<?php

namespace Yosmanyga\Test\Validation\Resource\Definition;

use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;

class ObjectDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $definition = new ObjectDefinition();
        $attributes = array(
            'class', 'validators'
        );
        foreach ($attributes as $attribute) {
            $this->assertAttributeEmpty($attribute, $definition);
        }
    }

    public function testCreateValidator()
    {
        $definition = new ObjectDefinition();
        $this->assertInstanceOf('\Yosmanyga\Validation\Validator\ObjectValidator', $definition->createValidator());
    }
}