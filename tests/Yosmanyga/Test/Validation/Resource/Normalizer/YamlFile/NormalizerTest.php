<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer;
use Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            new YamlFileDelegatorNormalizer(array(
                new ValueNormalizer(),
                new ExpressionNormalizer(),
                new ArrayNormalizer(),
                new ObjectReferenceNormalizer()
            )),
            'delegator',
            $normalizer
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::supports
     */
    public function testSupports()
    {
        $normalizer = new Normalizer();
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $data = 'foo';
        $resource = new Resource();
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator->expects($this->once())->method('supports')->with($data, $resource);
        $property->setValue($normalizer, $delegator);
        $normalizer->supports($data, $resource);
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = $this->getMock(
            'Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer',
            array('normalizeProperties', 'createDefinition')
        );
        $resource = new Resource();
        $normalizer
            ->expects($this->once())
            ->method('normalizeProperties')
            ->with('foo', $resource)
            ->will($this->returnValue('bar'));
        $normalizer
            ->expects($this->once())
            ->method('createDefinition')
            ->with('ClassX', array('properties' => 'bar'));
        /** @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer $normalizer */
        $normalizer->normalize(
            array(
                'key' => 'ClassX',
                'value' => array(
                    'properties' => 'foo'
                )
            ),
            $resource
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::normalizeProperties
     * @expectedException \RuntimeException
     * @dataProvider provideInvalidDataForNormalizeProperties
     */
    public function testNormalizePropertiesThrowsExceptionWithInValidData($data)
    {
        $normalizer = new Normalizer();
        $method = new \ReflectionMethod($normalizer, 'normalizeProperties');
        $method->setAccessible(true);
        $method->invoke($normalizer, $data, new Resource());
    }

    public function provideInvalidDataForNormalizeProperties()
    {
        return array(
            // With no 'validator' key
            array(
                array(
                    'property1' => array(
                        'foo' => 'bar'
                    )
                )
            ),
            // With extra keys
            array(
                array(
                    'property1' => array(
                        'validator' => 'Foo',
                        'foo' => 'bar'
                    )
                )
            )
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::normalizeProperties
     */
    public function testNormalizeProperties()
    {
        $normalizer = new Normalizer();
        $resource = new Resource();
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator
            ->expects($this->at(0))->method('normalize')
            ->with(
                array(
                    'key' => 'validatorX',
                    'value' => array(
                        'fooX' => 'barX'
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorX'));
        $delegator
            ->expects($this->at(1))->method('normalize')
            ->with(
                array(
                    'key' => 'validatorY',
                    'value' => array(
                        'fooY' => 'barY'
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorY'));
        $delegator
            ->expects($this->at(2))->method('normalize')
            ->with(
                array(
                    'key' => 'validatorZ',
                    'value' => array(
                        'fooZ' => 'barZ'
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorZ'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeProperties');
        $method->setAccessible(true);
        $this->assertEquals(
            array(
                'property1' => array('ValidatorX'),
                'property2' => array('ValidatorY', 'ValidatorZ')
            ),
            $method->invoke(
                $normalizer,
                array(
                    'property1' => array(
                        'validatorX' => array(
                            'fooX' => 'barX'
                        )
                    ),
                    'property2' => array(
                        1 => array(
                            'validator' => 'validatorY',
                            'options' => array(
                                'fooY' => 'barY'
                            )
                        ),
                        2 => array(
                            'validator' => 'validatorZ',
                            'options' => array(
                                'fooZ' => 'barZ'
                            )
                        )
                    )
                ),
                new Resource()
            )
        );
    }
}
