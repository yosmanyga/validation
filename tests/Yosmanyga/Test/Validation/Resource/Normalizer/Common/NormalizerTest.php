<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\Common;

use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer::createDefinition
     */
    public function testCreateDefinition()
    {
        /** @var \Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer $normalizer */
        $normalizer = $this->getMockForAbstractClass('Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer');
        $method = new \ReflectionMethod($normalizer, 'createDefinition');
        $method->setAccessible(true);
        $definition = new ObjectDefinition();
        $definition->class = 'ClassX';
        $definition->validators = ['foo'];
        $this->assertEquals($definition, $method->invoke($normalizer, 'ClassX', ['foo']));
    }
}
