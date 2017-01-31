<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Validation\Resource\Compiler\ArrayCompiler;
use Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
use Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler;
use Yosmanyga\Validation\Resource\Compiler\ValueCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ScalarValidator;

class ObjectCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectCompiler::__construct
     */
    public function testConstruct()
    {
        $compiler = new ObjectCompiler();
        $this->assertAttributeEquals(
            [
                new ValueCompiler(),
                new ExpressionCompiler(),
                new ArrayCompiler([
                    new ValueCompiler(),
                    new ExpressionCompiler(),
                ]),
                new ObjectReferenceCompiler(),
            ],
            'compilers',
            $compiler
        );

        $compiler = new ObjectCompiler(['foo']);
        $this->assertAttributeEquals(['foo'], 'compilers', $compiler);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectCompiler::supports
     */
    public function testSupports()
    {
        $compiler = new ObjectCompiler();
        $this->assertTrue($compiler->supports(new ObjectDefinition()));
        $this->assertFalse($compiler->supports('foo'));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectCompiler::compile
     */
    public function testCompile()
    {
        $compiler = new ObjectCompiler();
        $definition = new ObjectDefinition();
        $definition->validators = [
            'properties' => [
                'property1' => [
                    new ValueDefinition(),
                ],
            ],
        ];
        $this->assertEquals(
            new ObjectValidator([
                'property1' => [
                    new ScalarValidator(),
                ],
            ]),
            $compiler->compile($definition)
        );
    }
}
