<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Compiler\DelegatorCompiler;
use Yosmanyga\Validation\Resource\Compiler\ArrayCompiler;
use Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler;
use Yosmanyga\Validation\Resource\Compiler\ValueCompiler;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;

class ArrayCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ArrayCompiler::__construct
     */
    public function testConstruct()
    {
        $compiler = new ArrayCompiler();
        $this->assertAttributeEquals(
            new DelegatorCompiler([
                new ValueCompiler(),
                new ExpressionCompiler(),
            ]),
            'compiler',
            $compiler
        );

        $compiler = new ArrayCompiler(['foo']);
        $this->assertAttributeEquals(new DelegatorCompiler(['foo']), 'compiler', $compiler);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ArrayCompiler::supports
     */
    public function testSupports()
    {
        $compiler = new ArrayCompiler();
        $this->assertTrue($compiler->supports(new ArrayDefinition()));
        $this->assertFalse($compiler->supports('foo'));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ArrayCompiler::compile
     */
    public function testCompile()
    {
        $compiler = new ArrayCompiler();
        $definition = new ArrayDefinition();
        $this->assertEquals(new ArrayValidator($definition->export()), $compiler->compile($definition));
    }
}
