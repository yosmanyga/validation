<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer;

class ArrayNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new MockArrayNormalizer(['foo']);
        $this->assertAttributeEquals(
            new DelegatorNormalizer([
                'foo',
            ]),
            'normalizer',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer::supports
     */
    public function testSupports()
    {
        $resource = new Resource();
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer');
        $this->assertTrue($normalizer->supports('Array', $resource));
        $this->assertFalse($normalizer->supports('foo', $resource));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer::createDefinition
     */
    public function testCreateDefinition()
    {
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer');
        $method = new \ReflectionMethod($normalizer, 'createDefinition');
        $method->setAccessible(true);
        $definition = new ArrayDefinition();
        $definition->allowExtra = true;
        $this->assertEquals($definition, $method->invoke($normalizer, ['allowExtra' => true]));
    }
}

class MockArrayNormalizer extends ArrayNormalizer
{
    public function normalize($data, Resource $resource)
    {
    }
}
