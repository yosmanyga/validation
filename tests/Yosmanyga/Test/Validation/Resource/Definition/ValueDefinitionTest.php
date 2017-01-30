<?php

namespace Yosmanyga\Test\Validation\Resource\Definition;

use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

class ValueDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $definition = new ValueDefinition();
        $attributes = [
            'allowNull', 'type', 'eq', 'neq', 'iq', 'niq', 'gt', 'ge', 'lt',
            'le', 'in', 'nin', 'messages',
        ];
        foreach ($attributes as $attribute) {
            $this->assertAttributeEmpty($attribute, $definition);
        }
    }

    public function testCreateValidator()
    {
        $definition = new ValueDefinition();
        $this->assertInstanceOf('\Yosmanyga\Validation\Validator\ObjectValidator', $definition->createValidator());
    }
}
