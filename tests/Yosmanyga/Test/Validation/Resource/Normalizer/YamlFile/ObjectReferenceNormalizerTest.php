<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer;

class ObjectReferenceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $this->assertTrue($normalizer->supports(['key' => 'Object'], new Resource()));
        $this->assertFalse($normalizer->supports(['key' => 'bar'], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $definition = new ObjectReferenceDefinition();
        $definition->class = 'foo';
        $this->assertEquals(
            $definition,
            $normalizer->normalize(['value' => ['class' => 'foo']], new Resource())
        );
    }
}
