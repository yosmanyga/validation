<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ExpressionPropertyValidator;

class ExpressionCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler::supports
     */
    public function testSupports()
    {
        $compiler = new ExpressionCompiler();
        $this->assertTrue($compiler->supports(new ExpressionDefinition()));
        $this->assertFalse($compiler->supports('foo'));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler::compile
     */
    public function testCompile()
    {
        $compiler = $this->getMock('Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler', ['createValidator']);
        $definition = $this->getMock('Yosmanyga\Resource\Definition\DefinitionInterface');
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $compiler
            ->expects($this->once())->method('createValidator')->with($definition)
            ->will($this->returnValue($validator));
        $validator->expects($this->once())->method('validate')->with($definition);
        $definition->expects($this->once())->method('export')->will($this->returnValue(['expression' => 'foo', 'foo' => 'bar']));
        /* @var \Yosmanyga\Resource\Compiler\CompilerInterface $compiler */
        $this->assertEquals(
            new ExpressionPropertyValidator('foo', ['foo' => 'bar']),
            $compiler->compile($definition)
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler::createValidator
     */
    public function testCreateValidator()
    {
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $validator */
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $definition = $this->getMock('Yosmanyga\Validation\Validator\ValidatedInterface');
        $definition->expects($this->once())->method('createValidator')->will($this->returnValue($validator));
        $compiler = new ExpressionCompiler();
        $method = new \ReflectionMethod($compiler, 'createValidator');
        $method->setAccessible(true);
        $this->assertEquals(
            new ExceptionValidator($validator),
            $method->invoke($compiler, $definition)
        );
    }
}
