<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer;
use Yosmanyga\Resource\Normalizer\XmlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            new XmlFileDelegatorNormalizer(array(
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::supports
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::normalize
     */
    public function testNormalize()
    {
        $normalizer = $this->getMock(
            'Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer',
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
        /** @var \Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer $normalizer */
        $normalizer->normalize(
            array(
                'value' => array(
                    'name' => 'ClassX',
                    'property' => 'foo'
                )
            ),
            $resource
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::normalizeProperties
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
            ),
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::normalizeProperties
     */
    public function testNormalizeProperties()
    {
        $normalizer = $this->getMock(
            'Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer',
            array('normalizeValidators')
        );
        $method = new \ReflectionMethod($normalizer, 'normalizeProperties');
        $method->setAccessible(true);
        $resource = new Resource();
        $normalizer
            ->expects($this->at(0))->method('normalizeValidators')
            ->with(
                array(
                    'name' => 'validatorX',
                    'option' => array(
                        'name' => 'fooX',
                        'value' => 'barX'
                    )
                ),
                $resource
            )
            ->will($this->returnValue(array('ValidatorX')));
        $normalizer
            ->expects($this->at(1))->method('normalizeValidators')
            ->with(
                array(
                    0 => array(
                        'name' => 'validatorY',
                        'option' => array(
                            'name' => 'fooY',
                            'value' => 'barY'
                        )
                    ),
                    1 => array(
                        'name' => 'validatorZ',
                        'option' => array(
                            'name' => 'fooZ',
                            'value' => 'barZ'
                        )
                    )
                ),
                $resource
            )
            ->will($this->returnValue(array('ValidatorY', 'ValidatorZ')));
        $this->assertEquals(
            array(
                'property1' => array('ValidatorX'),
                'property2' => array('ValidatorY', 'ValidatorZ')
            ),
            $method->invoke(
                $normalizer,
                array(
                    0 => array(
                        'name' => 'property1',
                        'validator' => array(
                            'name' => 'validatorX',
                            'option' => array(
                                'name' => 'fooX',
                                'value' => 'barX'
                            )
                        )
                    ),
                    1 => array(
                        'name' => 'property2',
                        'validator' => array(
                            0 => array(
                                'name' => 'validatorY',
                                'option' => array(
                                    'name' => 'fooY',
                                    'value' => 'barY'
                                )
                            ),
                            1 => array(
                                'name' => 'validatorZ',
                                'option' => array(
                                    'name' => 'fooZ',
                                    'value' => 'barZ'
                                )
                            )
                        )
                    )
                ),
                new Resource()
            )
        );
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::normalizeValidators
     */
    public function testNormalizeValidators()
    {
        $resource = new Resource();
        $normalizer = new Normalizer();
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator
            ->expects($this->at(0))->method('normalize')
            ->with(
                array(
                    'value' => array(
                        'name' => 'validatorX',
                        'option' => array(
                            'fooX' => 'barX'
                        )
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorX'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeValidators');
        $method->setAccessible(true);
        $this->assertEquals(
            array(
                'ValidatorX'
            ),
            $method->invoke(
                $normalizer,
                array(
                    'name' => 'validatorX',
                    'option' => array(
                        'fooX' => 'barX'
                    )
                ),
                new Resource()
            )
        );

        $resource = new Resource();
        $normalizer = new Normalizer();
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator
            ->expects($this->at(0))->method('normalize')
            ->with(
                array(
                    'value' => array(
                        'name' => 'validatorY',
                        'option' => array(
                            'fooY' => 'barY'
                        )
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorY'));
        $delegator
            ->expects($this->at(1))->method('normalize')
            ->with(
                array(
                    'value' => array(
                        'name' => 'validatorZ',
                        'option' => array(
                            'fooZ' => 'barZ'
                        )
                    )
                ),
                $resource
            )
            ->will($this->returnValue('ValidatorZ'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeValidators');
        $method->setAccessible(true);
        $this->assertEquals(
            array(
                'ValidatorY', 'ValidatorZ'
            ),
            $method->invoke(
                $normalizer,
                array(
                    0 => array(
                        'name' => 'validatorY',
                        'option' => array(
                            'fooY' => 'barY'
                        )
                    ),
                    1 => array(
                        'name' => 'validatorZ',
                        'option' => array(
                            'fooZ' => 'barZ'
                        )
                    )
                ),
                new Resource()
            )
        );
    }
}