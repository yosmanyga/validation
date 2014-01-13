<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;

class ArrayNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer::__construct
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new ArrayNormalizer();
        $this->assertTrue($normalizer->supports(array('key' => 'Array'), new Resource()));
        $this->assertTrue($normalizer->supports(array('key' => 'Validator\Array'), new Resource()));
        $this->assertFalse($normalizer->supports(array('key' => 'Validator\bar'), new Resource()));
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = new ArrayNormalizer();
        $definition = new ArrayDefinition();
        $definition->requiredKeys = array();
        $this->assertEquals(
            $definition,
            $normalizer->normalize(
                array(
                    'value' =>
                        array('requiredKeys' => array())
                ),
                new Resource()
            )
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer::normalize
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
                        'map' => array(
                            'validator' => 'Value',
                            'options' => array(
                                'allowNull' => true
                            )
                        )
                    )
                ),
                new Resource()
            )
        );
    }
}