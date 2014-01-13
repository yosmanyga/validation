<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;

class ExpressionNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer::supports
     */
    public function testSupports()
    {
        $resource = new Resource();
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer');
        $this->assertTrue($normalizer->supports('Expression', $resource));
        $this->assertFalse($normalizer->supports('foo', $resource));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer::createDefinition
     */
    public function testCreateDefinition()
    {
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer');
        $method = new \ReflectionMethod($normalizer, 'createDefinition');
        $method->setAccessible(true);
        $definition = new ExpressionDefinition();
        $definition->expression = 'foo';
        $this->assertEquals($definition, $method->invoke($normalizer, array('expression' => 'foo')));
    }
}