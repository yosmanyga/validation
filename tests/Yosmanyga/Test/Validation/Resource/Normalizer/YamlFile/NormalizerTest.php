<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            new YamlFileDelegatorNormalizer([
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
        /* @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer $normalizer */
        $normalizer->normalize(
            [
                'key'   => 'ClassX',
                'value' => [
                    'properties' => 'foo',
                ],
            ],
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
                [
                    'key'   => 'validatorX',
                    'value' => [
                        'fooX' => 'barX',
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorX'));
        $delegator
            ->expects($this->at(1))->method('normalize')
            ->with(
                [
                    'key'   => 'validatorY',
                    'value' => [
                        'fooY' => 'barY',
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorY'));
        $delegator
            ->expects($this->at(2))->method('normalize')
            ->with(
                [
                    'key'   => 'validatorZ',
                    'value' => [
                        'fooZ' => 'barZ',
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorZ'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $method = new \ReflectionMethod($normalizer, 'normalizeProperties');
        $method->setAccessible(true);
        $this->assertEquals(
            [
                'property1' => ['ValidatorX'],
                'property2' => ['ValidatorY', 'ValidatorZ'],
            ],
            $method->invoke(
                $normalizer,
                [
                    'property1' => [
                        'validatorX' => [
                            'fooX' => 'barX',
                        ],
                    ],
                    'property2' => [
                        1 => [
                            'validator' => 'validatorY',
                            'options'   => [
                                'fooY' => 'barY',
                            ],
                        ],
                        2 => [
                            'validator' => 'validatorZ',
                            'options'   => [
                                'fooZ' => 'barZ',
                            ],
                        ],
                    ],
                ],
                new Resource()
            )
        );
    }
}
