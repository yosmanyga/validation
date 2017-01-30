<?php

namespace Yosmanyga\Test\Validation\Resource\Definition;

use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;

class ObjectReferenceDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $definition = new ObjectReferenceDefinition();
        $attributes = [
            'class',
        ];
        foreach ($attributes as $attribute) {
            $this->assertAttributeEmpty($attribute, $definition);
        }
    }

    public function testCreateValidator()
    {
        $definition = new ObjectReferenceDefinition();
        $this->assertInstanceOf('\Yosmanyga\Validation\Validator\ObjectValidator', $definition->createValidator());
    }
}
