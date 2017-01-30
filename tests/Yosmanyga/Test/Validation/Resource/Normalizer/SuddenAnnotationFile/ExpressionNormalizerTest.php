<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer as YamlFileExpressionNormalizer;

class ExpressionNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ExpressionNormalizer();
        $this->assertAttributeEquals(
            new YamlFileExpressionNormalizer(),
            'yamlFileNormalizer',
            $normalizer
        );

        $yamlFileNormalizer = $this->getMock('Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer');
        $normalizer = new ExpressionNormalizer($yamlFileNormalizer);
        $this->assertAttributeEquals(
            $yamlFileNormalizer,
            'yamlFileNormalizer',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ExpressionNormalizer();
        $this->assertTrue($normalizer->supports(['key' => 'Expression'], new Resource()));
        $this->assertTrue($normalizer->supports(['key' => 'Validation\Expression'], new Resource()));
        $this->assertFalse($normalizer->supports(['key' => 'bar'], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer::normalize
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
