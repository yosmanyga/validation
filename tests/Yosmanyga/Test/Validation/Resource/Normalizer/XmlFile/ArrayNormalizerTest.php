<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer;

class ArrayNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::__construct
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ArrayNormalizer();
        $this->assertTrue($normalizer->supports(['value' => ['name' => 'Array']], new Resource()));
        $this->assertFalse($normalizer->supports(['value' => ['name' => 'bar']], new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::normalize
     */
    public function testNormalize()
    {
        // One option
        $normalizer = new ArrayNormalizer();
        $definition = new ArrayDefinition();
        $definition->requiredKeys = [];
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => [
                        'name'   => 'Array',
                        'option' => [
                            'name'  => 'requiredKeys',
                            'value' => [],
                        ],
                    ],
                ],
                new Resource()
            )
        );

        // Many options
        $definition = new ArrayDefinition();
        $definition->allowExtra = false;
        $definition->deniedKeys = ['bar'];
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                [
                    'value' => [
                        'name'   => 'Array',
                        'option' => [
                            [
                                'name'  => 'allowExtra',
                                'value' => false,
                            ],
                            [
                                'name'  => 'deniedKeys',
                                'value' => ['bar'],
                            ],
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::normalize
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
                        'name'   => 'Array',
                        'option' => [
                            [
                                'name'      => 'map',
                                'validator' => [
                                    'name'   => 'Value',
                                    'option' => [
                                        'name'  => 'allowNull',
                                        'value' => true,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
