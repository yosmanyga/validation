<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;

class ArrayNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new ArrayNormalizer();
        $this->assertAttributeEquals(
            new DelegatorNormalizer(array(
                new ValueNormalizer(),
                new ExpressionNormalizer()
            )),
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
        $this->assertTrue($normalizer->supports(array('value' => array('name' => 'Array')), new Resource()));
        $this->assertFalse($normalizer->supports(array('value' => array('name' => 'bar')), new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer::normalize
     */
    public function testNormalize()
    {
        // One option
        $normalizer = new ArrayNormalizer();
        $definition = new ArrayDefinition();
        $definition->requiredKeys = array();
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                array(
                    'value' => array(
                        'name' => 'Array',
                        'option' => array(
                            'name' => 'requiredKeys',
                            'value' => array()
                        )
                    )
                ),
                new Resource()
            )
        );

        // Many options
        $definition = new ArrayDefinition();
        $definition->allowExtra = false;
        $definition->deniedKeys = array('bar');
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                array(
                    'value' => array(
                        'name' => 'Array',
                        'option' => array(
                            array(
                                'name' => 'allowExtra',
                                'value' => false
                            ),
                            array(
                                'name' => 'deniedKeys',
                                'value' => array('bar')
                            )
                        )
                    )
                ),
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
                array(
                    'value' => array(
                        'name' => 'Array',
                        'option' => array(
                            array(
                                'name' => 'map',
                                'validator' => array(
                                    'name' => 'Value',
                                    'option' => array(
                                        'name' => 'allowNull',
                                        'value' => true
                                    )
                                )
                            )
                        )
                    )
                ),
                new Resource()
            )
        );
    }
}
