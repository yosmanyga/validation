<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Resource\Util\XmlKit;

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
        $this->assertTrue($normalizer->supports(array('value' => array('name' => 'Object')), new Resource()));
        $this->assertFalse($normalizer->supports(array('value' => array('name' => 'bar')), new Resource()));
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
                array(
                    'value' => array(
                        'name' => 'Value',
                        'option' => array(
                            'name' => 'class',
                            'value' => 'foo'
                        )
                    )
                ),
                new Resource()
            )
        );
    }
}
