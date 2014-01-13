<?php

namespace Yosmanyga\Test\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Normalizer\DirectoryNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\Normalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer as YamlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer as XmlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer as SuddenAnnotationFileNormalizer;

class NormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Yosmanyga\Validation\Resource\Normalizer\Normalizer::__construct
     */
    public function testConstruct()
    {
        $normalizer = new Normalizer(array('foo'));
        $this->assertAttributeEquals(
            array(
                'foo'
            ),
            'normalizers',
            $normalizer
        );

        $normalizer = new Normalizer();
        $this->assertAttributeEquals(
            array(
                new YamlFileNormalizer(),
                new XmlFileNormalizer,
                new SuddenAnnotationFileNormalizer(),
                new DirectoryNormalizer(array(
                    new YamlFileNormalizer(),
                    new XmlFileNormalizer,
                    new SuddenAnnotationFileNormalizer()
                ))
            ),
            'normalizers',
            $normalizer
        );
    }
}
