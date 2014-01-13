<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
use Yosmanyga\Validation\Resource\Compiler\ValueCompiler;
use Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler;
use Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler;
use Yosmanyga\Validation\Resource\Compiler\ArrayCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValueValidator;

class ObjectCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectCompiler::__construct
     */
    public function testConstruct()
    {
        $compiler = new ObjectCompiler();
        $this->assertAttributeEquals(
            array(
                new ValueCompiler(),
                new ExpressionCompiler(),
                new ArrayCompiler(array(
                    new ValueCompiler(),
                    new ExpressionCompiler()
                )),
                new ObjectReferenceCompiler()
            ),
            'compilers',
            $compiler
        );

        $compiler = new ObjectCompiler(array('foo'));
        $this->assertAttributeEquals(array('foo'), 'compilers', $compiler);
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
        $definition->validators = array(
            'properties' => array(
                'property1' => array(
                    new ValueDefinition()
                )
            )
        );
        $this->assertEquals(
            new ObjectValidator(array(
                'property1' => array(
                    new ValueValidator()
                )
            )),
            $compiler->compile($definition)
        );
    }
}
