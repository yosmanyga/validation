<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer;

class ArrayNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ArrayNormalizer();
        $this->assertAttributeEquals(
            new DelegatorNormalizer([
                new ValueNormalizer(),
                new ExpressionNormalizer(),
            ]),
            'normalizer',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ArrayNormalizer();
        $this->assertTrue($normalizer->supports(['key' => 'Array'], new Resource()));
        $this->assertFalse($normalizer->supports(['key' => 'bar'], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ArrayNormalizer();
        $definition = new ArrayDefinition();
        $definition->requiredKeys = [];
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => ['requiredKeys' => []],
                ],
                new Resource()
            )
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer::normalize
     */
    public function testNormalizeWithMap()
    {
        $valueDefinition = new ValueDefinition();
        $valueDefinition->allowNull = true;
        $arrayDefinition = new ArrayDefinition();
        $arrayDefinition->map = $valueDefinition;
        $normalizer = new ArrayNormalizer();
        $this->assertEquals(
            $arrayDefinition,
            $normalizer->normalize(
                [
                    'value' => [
                        'map' => [
                            'validator' => 'Value',
                            'options'   => [
                                'allowNull' => true,
                            ],
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
