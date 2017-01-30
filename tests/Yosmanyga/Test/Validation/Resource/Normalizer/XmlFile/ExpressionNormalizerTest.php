<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Util\XmlKit;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer;

class ExpressionNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ExpressionNormalizer();
        $this->assertAttributeEquals(
            new XmlKit(),
            'xmlKit',
            $normalizer
        );

        $xmlKit = $this->getMock('Yosmanyga\Resource\Util\XmlKit');
        $normalizer = new ExpressionNormalizer($xmlKit);
        $this->assertAttributeEquals(
            $xmlKit,
            'xmlKit',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ExpressionNormalizer();
        $this->assertTrue($normalizer->supports(['value' => ['name' => 'Expression']], new Resource()));
        $this->assertFalse($normalizer->supports(['value' => ['name' => 'bar']], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ExpressionNormalizer();
        $definition = new ExpressionDefinition();
        $definition->expression = 'foo';
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => [
                        'name'   => 'Expresison',
                        'option' => [
                            'name'  => 'expression',
                            'value' => 'foo',
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
