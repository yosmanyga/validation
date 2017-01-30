<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ObjectReferenceNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer as YamlFileObjectReferenceNormalizer;

class ObjectReferenceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ObjectReferenceNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $this->assertAttributeEquals(
            new YamlFileObjectReferenceNormalizer(),
            'yamlFileNormalizer',
            $normalizer
        );

        $yamlFileNormalizer = $this->getMock('Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer');
        $normalizer = new ObjectReferenceNormalizer($yamlFileNormalizer);
        $this->assertAttributeEquals(
            $yamlFileNormalizer,
            'yamlFileNormalizer',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ObjectReferenceNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $this->assertTrue($normalizer->supports(['key' => 'Object'], new Resource()));
        $this->assertTrue($normalizer->supports(['key' => 'Validation\Object'], new Resource()));
        $this->assertFalse($normalizer->supports(['key' => 'bar'], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ObjectReferenceNormalizer::normalize
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
