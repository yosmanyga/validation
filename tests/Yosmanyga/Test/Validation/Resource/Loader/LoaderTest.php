<?php

namespace Yosmanyga\Test\Validation\Resource\Loader;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Loader\Loader;
use Yosmanyga\Resource\Reader\Iterator\DelegatorReader;
use Yosmanyga\Validation\Resource\Normalizer\Normalizer;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
use Yosmanyga\Resource\Cacher\Cacher;
use Yosmanyga\Validation\Validator\ObjectValidator;

class LoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Loader\Loader::__construct
     */
    public function testConstruct()
    {
        $loader = new Loader();
        $this->assertAttributeEquals(new DelegatorReader(), 'reader', $loader);
        $this->assertAttributeEquals(new Normalizer(), 'normalizer', $loader);
        $this->assertAttributeEquals(new ObjectCompiler(), 'compiler', $loader);
        $this->assertAttributeEquals(new Cacher(), 'cacher', $loader);

        $reader = new DelegatorReader();
        $normalizer = new Normalizer();
        $compiler = new ObjectCompiler();
        $cacher = new Cacher();
        $loader = new Loader($reader, $normalizer, $compiler, $cacher);
        $this->assertAttributeSame($reader, 'reader', $loader);
        $this->assertAttributeSame($normalizer, 'normalizer', $loader);
        $this->assertAttributeSame($compiler, 'compiler', $loader);
        $this->assertAttributeSame($cacher, 'cacher', $loader);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Loader\Loader::load
     */
    public function testLoadWithCache()
    {
        $resource = new Resource();
        $cacher = $this->getMock('Yosmanyga\Resource\Cacher\CacherInterface');
        $cacher->expects($this->once())->method('check')->with($resource)->will($this->returnValue(true));
        $cacher->expects($this->once())->method('retrieve')->with($resource)->will($this->returnValue('foo'));
        $reader = $this->getMock('Yosmanyga\Resource\Reader\Iterator\ReaderInterface');
        $reader->expects($this->never())->method('open');
        /** @var \Yosmanyga\Resource\Cacher\CacherInterface $cacher */
        $loader = new Loader(null, null, null, $cacher);
        $loader->load($resource);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Loader\Loader::load
     */
    public function testLoadWithEmptyResource()
    {
        $resource = new Resource();
        $cacher = $this->getMock('Yosmanyga\Resource\Cacher\CacherInterface');
        $reader = $this->getMock('Yosmanyga\Resource\Reader\Iterator\ReaderInterface');
        $loader = $this->getMock(
            'Yosmanyga\Validation\Resource\Loader\Loader',
            array('fillObjectValidators'),
            array($reader, null, null, $cacher)
        );
        /** @var \PHPUnit_Framework_MockObject_MockObject $cacher */
        /** @var \PHPUnit_Framework_MockObject_MockObject $reader */
        $cacher->expects($this->once())->method('check')->with($resource)->will($this->returnValue(false));
        $reader->expects($this->once())->method('open')->with($resource);
        $reader->expects($this->once())->method('current')->will($this->returnValue(false));
        $loader->expects($this->once())->method('fillObjectValidators')->with(array(), array())->will($this->returnValue(array()));
        $cacher->expects($this->once())->method('store')->with(array(), $resource);
        /** @var \Yosmanyga\Resource\Loader\LoaderInterface $loader */
        $loader->load($resource);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Loader\Loader::load
     */
    public function testLoadWithData()
    {
        $resource = new Resource();
        $reader = $this->getMock('Yosmanyga\Resource\Reader\Iterator\ReaderInterface');
        $normalizer = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $compiler = $this->getMock('Yosmanyga\Resource\Compiler\CompilerInterface');
        $cacher = $this->getMock('Yosmanyga\Resource\Cacher\CacherInterface');
        $loader = $this->getMock(
            'Yosmanyga\Validation\Resource\Loader\Loader',
            array('fillObjectValidators'),
            array($reader, $normalizer, $compiler, $cacher)
        );
        $cacher->expects($this->once())->method('check')->with($resource)->will($this->returnValue(false));
        $reader->expects($this->once())->method('open')->with($resource);
        $reader->expects($this->at(1))->method('current')->will($this->returnValue(array('foo' => 'bar')));
        $definition = new ObjectDefinition();
        $definition->class = 'classX';
        $definition->validators = array('validator1');
        $normalizer->expects($this->once())->method('normalize')->with(array('foo' => 'bar'), $resource)->will($this->returnValue($definition));
        $validator = new ObjectValidator();
        $compiler->expects($this->once())->method('compile')->with($definition)->will($this->returnValue($validator));
        $reader->expects($this->at(2))->method('current')->will($this->returnValue(false));
        $loader->expects($this->once())->method('fillObjectValidators')->with(array('classX' => $definition), array('classX' => $validator))->will($this->returnValue(array('classX' => $validator)));
        $cacher->expects($this->once())->method('store')->with(array('classX' => $validator), $resource);
        /** @var \Yosmanyga\Validation\Resource\Loader\Loader $loader */
        $this->assertEquals(
            array('classX' => $validator),
            $loader->load($resource)
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Loader\Loader::fillObjectValidators
     */
    public function testFillObjectValidators()
    {
        $loader = new Loader();
        $method = new \ReflectionMethod($loader, 'fillObjectValidators');
        $method->setAccessible(true);
        $objectReferenceDefinition = new ObjectReferenceDefinition();
        $objectReferenceDefinition->class = 'classY';
        $definition = new ObjectDefinition();
        $definition->class = 'classX';
        $definition->validators = array(
            'properties' => array(
                'propertyY' => array(
                    $objectReferenceDefinition
                )
            )
        );
        $this->assertEquals(
            array(
                'classX' => new ObjectValidator(array(
                    'propertyY' => new ObjectValidator(array('foo'))
                )),
                'classY' => new ObjectValidator(array('foo'))
            ),
            $method->invoke(
                $loader,
                array(
                    'classX' => $definition
                ),
                array(
                    'classX' => new ObjectValidator(),
                    'classY' => new ObjectValidator(array('foo'))
                )
            )
        );
    }
}