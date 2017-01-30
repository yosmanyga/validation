<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Validation\Resource\Compiler\ValueCompiler;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ValueValidator;

class ValueCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ValueCompiler::supports
     */
    public function testSupports()
    {
        $compiler = new ValueCompiler();
        $this->assertTrue($compiler->supports(new ValueDefinition()));
        $this->assertFalse($compiler->supports('foo'));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ValueCompiler::compile
     */
    public function testCompile()
    {
        $compiler = $this->getMock('Yosmanyga\Validation\Resource\Compiler\ValueCompiler', ['createValidator']);
        $definition = $this->getMock('Yosmanyga\Resource\Definition\DefinitionInterface');
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $compiler
            ->expects($this->once())->method('createValidator')->with($definition)
            ->will($this->returnValue($validator));
        $validator->expects($this->once())->method('validate')->with($definition);
        $definition->expects($this->once())->method('export')->will($this->returnValue(['foo']));
        /* @var \Yosmanyga\Resource\Compiler\CompilerInterface $compiler */
        $this->assertEquals(
            new ValueValidator(['foo']),
            $compiler->compile($definition)
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ValueCompiler::createValidator
     */
    public function testCreateValidator()
    {
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $validator */
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $definition = $this->getMock('Yosmanyga\Validation\Validator\ValidatedInterface');
        $definition->expects($this->once())->method('createValidator')->will($this->returnValue($validator));
        $compiler = new ValueCompiler();
        $method = new \ReflectionMethod($compiler, 'createValidator');
        $method->setAccessible(true);
        $this->assertEquals(
            new ExceptionValidator($validator),
            $method->invoke($compiler, $definition)
        );
    }
}
