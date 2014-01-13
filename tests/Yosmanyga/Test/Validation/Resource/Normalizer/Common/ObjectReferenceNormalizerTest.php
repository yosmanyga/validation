<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;

class ObjectReferenceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer::supports
     */
    public function testSupports()
    {
        $resource = new Resource();
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer');
        $this->assertTrue($normalizer->supports('Object', $resource));
        $this->assertFalse($normalizer->supports('foo', $resource));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer::createDefinition
     */
    public function testCreateDefinition()
    {
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer');
        $method = new \ReflectionMethod($normalizer, 'createDefinition');
        $method->setAccessible(true);
        $definition = new ObjectReferenceDefinition();
        $definition->class = 'ClassX';
        $this->assertEquals($definition, $method->invoke($normalizer, array('class' => 'ClassX')));
    }
}
