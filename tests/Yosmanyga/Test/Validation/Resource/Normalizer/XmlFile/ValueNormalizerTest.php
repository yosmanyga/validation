<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Util\XmlKit;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer;

class ValueNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ValueNormalizer();
        $this->assertAttributeEquals(
            new XmlKit(),
            'xmlKit',
            $normalizer
        );

        $xmlKit = $this->getMock('Yosmanyga\Resource\Util\XmlKit');
        $normalizer = new ValueNormalizer($xmlKit);
        $this->assertAttributeEquals(
            $xmlKit,
            'xmlKit',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ValueNormalizer();
        $this->assertTrue($normalizer->supports(['value' => ['name' => 'Value']], new Resource()));
        $this->assertFalse($normalizer->supports(['value' => ['name' => 'bar']], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ValueNormalizer();
        $definition = new ValueDefinition();
        $definition->allowNull = true;
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => [
                        'name'   => 'Value',
                        'option' => [
                            'name'  => 'allowNull',
                            'value' => true,
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
