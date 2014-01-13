<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class ObjectReferenceCompilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler::supports
     */
    public function testSupports()
    {
        $compiler = new ObjectReferenceCompiler();
        $this->assertTrue($compiler->supports(new ObjectReferenceDefinition()));
        $this->assertFalse($compiler->supports('foo'));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler::compile
     */
    public function testCompile()
    {
        $compiler = $this->getMock('Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler', array('createValidator'));
        $definition = $this->getMock('Yosmanyga\Resource\Definition\DefinitionInterface');
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $compiler
            ->expects($this->once())->method('createValidator')->with($definition)
            ->will($this->returnValue($validator));
        $validator->expects($this->once())->method('validate')->with($definition);
        /** @var \Yosmanyga\Resource\Compiler\CompilerInterface $compiler */
        $this->assertEquals(
            null,
            $compiler->compile($definition)
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Compiler\ObjectReferenceCompiler::createValidator
     */
    public function testCreateValidator()
    {
        /** @var \Yosmanyga\Validation\Validator\ValidatorInterface $validator */
        $validator = $this->getMock('Yosmanyga\Validation\Validator\ValidatorInterface');
        $definition = $this->getMock('Yosmanyga\Validation\Validator\ValidatedInterface');
        $definition->expects($this->once())->method('createValidator')->will($this->returnValue($validator));
        $compiler = new ObjectReferenceCompiler();
        $method = new \ReflectionMethod($compiler, 'createValidator');
        $method->setAccessible(true);
        $this->assertEquals(
            new ExceptionValidator($validator),
            $method->invoke($compiler, $definition)
        );
    }

}
