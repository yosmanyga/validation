<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Normalizer\DirectoryNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer as SuddenAnnotationFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer as XmlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer as YamlFileNormalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer(['foo']);
        $this->assertAttributeEquals(
            [
                'foo',
            ],
            'normalizers',
            $normalizer
        );

        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            [
                new YamlFileNormalizer(),
                new XmlFileNormalizer(),
                new SuddenAnnotationFileNormalizer(),
                new DirectoryNormalizer([
                    new YamlFileNormalizer(),
                    new XmlFileNormalizer(),
                    new SuddenAnnotationFileNormalizer(),
                ]),
            ],
            'normalizers',
            $normalizer
        );
    }
}
