<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer;

class ExpressionNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ExpressionNormalizer();
        $this->assertTrue($normalizer->supports(['key' => 'Expression'], new Resource()));
        $this->assertFalse($normalizer->supports(['key' => 'bar'], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ExpressionNormalizer();
        $definition = new ExpressionDefinition();
        $definition->expression = 'foo';
        $this->assertEquals(
            $definition,
            $normalizer->normalize(['value' => ['expression' => 'foo']], new Resource())
        );
    }
}
