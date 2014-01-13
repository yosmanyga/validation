<?php

namespace Yosmanyga\Test\Validation\Resource\Definition;

use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;

class ExpressionDefinitionTest extends \PHPUnit_Framework_TestCase
{
    public function testProperties()
    {
        $definition = new ExpressionDefinition();
        $attributes = array(
            'expression', 'message'
        );
        foreach ($attributes as $attribute) {
            $this->assertAttributeEmpty($attribute, $definition);
        }
    }

    public function testCreateValidator()
    {
        $definition = new ExpressionDefinition();
        $this->assertInstanceOf('\Yosmanyga\Validation\Validator\ObjectValidator', $definition->createValidator());
    }
}