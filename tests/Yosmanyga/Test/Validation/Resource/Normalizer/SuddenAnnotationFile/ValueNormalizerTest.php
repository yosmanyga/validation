<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer as YamlFileValueNormalizer;

class ValueNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ValueNormalizer();
        $this->assertAttributeEquals(
            new YamlFileValueNormalizer(),
            'yamlFileNormalizer',
            $normalizer
        );

        $yamlFileNormalizer = $this->getMock('Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer');
        $normalizer = new ValueNormalizer($yamlFileNormalizer);
        $this->assertAttributeEquals(
            $yamlFileNormalizer,
            'yamlFileNormalizer',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ValueNormalizer();
        $this->assertTrue($normalizer->supports(array('key' => 'Validation\Value'), new Resource()));
        $this->assertTrue($normalizer->supports(array('key' => 'Value'), new Resource()));
        $this->assertFalse($normalizer->supports(array('key' => 'bar'), new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer::normalize
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
