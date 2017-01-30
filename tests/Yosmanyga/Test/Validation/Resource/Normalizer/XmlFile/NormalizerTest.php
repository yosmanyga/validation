<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Normalizer\XmlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ObjectReferenceNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            new XmlFileDelegatorNormalizer([
                new ValueNormalizer(),
                new ExpressionNormalizer(),
                new ArrayNormalizer(),
                new ObjectReferenceNormalizer(),
            ]),
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
            ['normalizeProperties', 'createDefinition']
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
            ->with('ClassX', ['properties' => 'bar']);
        /* @var \Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer $normalizer */
        $normalizer->normalize(
            [
                'value' => [
                    'name'     => 'ClassX',
                    'property' => 'foo',
                ],
            ],
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
        return [
            // With no 'validator' key
            [
                [
                    'property1' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            // With extra keys
            [
                [
                    'property1' => [
                        'validator' => 'Foo',
                        'foo'       => 'bar',
                    ],
                ],
            ],
        ];
    }

    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer::normalizeProperties
     */
    public function testNormalizeProperties()
    {
        $normalizer = $this->getMock(
            'Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer',
            ['normalizeValidators']
        );
        $method = new \ReflectionMethod($normalizer, 'normalizeProperties');
        $method->setAccessible(true);
        $resource = new Resource();
        $normalizer
            ->expects($this->at(0))->method('normalizeValidators')
            ->with(
                [
                    'name'   => 'validatorX',
                    'option' => [
                        'name'  => 'fooX',
                        'value' => 'barX',
                    ],
                ],
                $resource
            )
            ->will($this->returnValue(['ValidatorX']));
        $normalizer
            ->expects($this->at(1))->method('normalizeValidators')
            ->with(
                [
                    0 => [
                        'name'   => 'validatorY',
                        'option' => [
                            'name'  => 'fooY',
                            'value' => 'barY',
                        ],
                    ],
                    1 => [
                        'name'   => 'validatorZ',
                        'option' => [
                            'name'  => 'fooZ',
                            'value' => 'barZ',
                        ],
                    ],
                ],
                $resource
            )
            ->will($this->returnValue(['ValidatorY', 'ValidatorZ']));
        $this->assertEquals(
            [
                'property1' => ['ValidatorX'],
                'property2' => ['ValidatorY', 'ValidatorZ'],
            ],
            $method->invoke(
                $normalizer,
                [
                    0 => [
                        'name'      => 'property1',
                        'validator' => [
                            'name'   => 'validatorX',
                            'option' => [
                                'name'  => 'fooX',
                                'value' => 'barX',
                            ],
                        ],
                    ],
                    1 => [
                        'name'      => 'property2',
                        'validator' => [
                            0 => [
                                'name'   => 'validatorY',
                                'option' => [
                                    'name'  => 'fooY',
                                    'value' => 'barY',
                                ],
                            ],
                            1 => [
                                'name'   => 'validatorZ',
                                'option' => [
                                    'name'  => 'fooZ',
                                    'value' => 'barZ',
                                ],
                            ],
                        ],
                    ],
                ],
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
                [
                    'value' => [
                        'name'   => 'validatorX',
                        'option' => [
                            'fooX' => 'barX',
                        ],
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorX'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeValidators');
        $method->setAccessible(true);
        $this->assertEquals(
            [
                'ValidatorX',
            ],
            $method->invoke(
                $normalizer,
                [
                    'name'   => 'validatorX',
                    'option' => [
                        'fooX' => 'barX',
                    ],
                ],
                new Resource()
            )
        );

        $resource = new Resource();
        $normalizer = new Normalizer();
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator
            ->expects($this->at(0))->method('normalize')
            ->with(
                [
                    'value' => [
                        'name'   => 'validatorY',
                        'option' => [
                            'fooY' => 'barY',
                        ],
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorY'));
        $delegator
            ->expects($this->at(1))->method('normalize')
            ->with(
                [
                    'value' => [
                        'name'   => 'validatorZ',
                        'option' => [
                            'fooZ' => 'barZ',
                        ],
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorZ'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeValidators');
        $method->setAccessible(true);
        $this->assertEquals(
            [
                'ValidatorY', 'ValidatorZ',
            ],
            $method->invoke(
                $normalizer,
                [
                    0 => [
                        'name'   => 'validatorY',
                        'option' => [
                            'fooY' => 'barY',
                        ],
                    ],
                    1 => [
                        'name'   => 'validatorZ',
                        'option' => [
                            'fooZ' => 'barZ',
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
