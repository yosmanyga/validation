<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

class ValueNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ValueNormalizer();
        $this->assertTrue($normalizer->supports(array('key' => 'Value'), new Resource()));
        $this->assertFalse($normalizer->supports(array('key' => 'bar'), new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ValueNormalizer();
        $definition = new ValueDefinition();
        $definition->allowNull = true;
        $this->assertEquals(
            $definition,
            $normalizer->normalize(array('value' => array('allowNull' => true)), new Resource())
        );
    }
}