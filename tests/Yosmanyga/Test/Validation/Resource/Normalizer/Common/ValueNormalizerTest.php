<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

class ValueNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer::supports
     */
    public function testSupports()
    {
        $resource = new Resource();
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer');
        $this->assertTrue($normalizer->supports('Value', $resource));
        $this->assertFalse($normalizer->supports('foo', $resource));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer::createDefinition
     */
    public function testCreateDefinition()
    {
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer');
        $method = new \ReflectionMethod($normalizer, 'createDefinition');
        $method->setAccessible(true);
        $definition = new ValueDefinition();
        $definition->allowNull = true;
        $this->assertEquals($definition, $method->invoke($normalizer, ['allowNull' => true]));
    }
}
