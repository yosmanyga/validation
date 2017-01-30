<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Util\XmlKit;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer;

class ObjectReferenceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $this->assertAttributeEquals(
            new XmlKit(),
            'xmlKit',
            $normalizer
        );

        $xmlKit = $this->getMock('Yosmanyga\Resource\Util\XmlKit');
        $normalizer = new ObjectReferenceNormalizer($xmlKit);
        $this->assertAttributeEquals(
            $xmlKit,
            'xmlKit',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $this->assertTrue($normalizer->supports(['value' => ['name' => 'Object']], new Resource()));
        $this->assertFalse($normalizer->supports(['value' => ['name' => 'bar']], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ObjectReferenceNormalizer();
        $definition = new ObjectReferenceDefinition();
        $definition->class = 'foo';
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => [
                        'name'   => 'Value',
                        'option' => [
                            'name'  => 'class',
                            'value' => 'foo',
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
