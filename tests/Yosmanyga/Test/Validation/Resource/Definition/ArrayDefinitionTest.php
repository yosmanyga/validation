<?php

namespace Yosmanyga\Test\Validation\Resource\Definition;

use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;

class ArrayDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $definition = new ArrayDefinition();
        $attributes = array(
            'requiredKeys', 'allowedKeys', 'map', 'deniedKeys', 'allowExtra', 'messages'
        );
        foreach ($attributes as $attribute) {
            $this->assertAttributeEmpty($attribute, $definition);
        }
    }

    public function testCreateValidator()
    {
        $definition = new ArrayDefinition();
        $this->assertInstanceOf('\Yosmanyga\Validation\Validator\ObjectValidator', $definition->createValidator());
    }
}