<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Normalizer\SuddenAnnotationFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ObjectReferenceNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            new SuddenAnnotationFileDelegatorNormalizer([
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer::supports
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
     * @covers Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer::normalize
     */
    public function testNormalize()
    {
        $resource = new Resource();
        $normalizer = $this->getMock(
            'Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer',
            ['createDefinition']
        );
        $delegator = $this->getMock('Yosmanyga\Resource\Normalizer\NormalizerInterface');
        $delegator
            ->expects($this->once())->method('normalize')
            ->with(
                [
                    'key'      => 'validator1',
                    'property' => 'property1',
                    'value'    => [
                        'fooX' => 'barX',
                    ],
                    'metadata' => [
                        'class' => 'ClassX',
                    ],
                ],
                $resource
            )
            ->will($this->returnValue('ValidatorX'));
        $property = new \ReflectionProperty($normalizer, 'delegator');
        $property->setAccessible(true);
        $property->setValue($normalizer, $delegator);
        $normalizer
            ->expects($this->once())
            ->method('createDefinition')
            ->with('ClassX', ['properties' => ['property1' => ['ValidatorX']]]);
        /* @var \Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer $normalizer */
        $normalizer->normalize(
            [
                'key'   => 0,
                'value' => [
                    [
                        'key'      => 'validator1',
                        'property' => 'property1',
                        'value'    => [
                            'fooX' => 'barX',
                        ],
                        'metadata' => [
                            'class' => 'ClassX',
                        ],
                    ],
                ],
            ],
            $resource
        );
    }
}
