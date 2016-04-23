<?php

namespace Yosmanyga\Validation\Resource\Normalizer;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Normalizer\DirectoryNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer as YamlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer as XmlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer as SuddenAnnotationFileNormalizer;

class Normalizer extends DelegatorNormalizer
{
    /**
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface[] $normalizers
     */
    public function __construct($normalizers = array())
    {
        $normalizers = $normalizers ?: array(
            new YamlFileNormalizer(),
            new XmlFileNormalizer(),
            new SuddenAnnotationFileNormalizer(),
            new DirectoryNormalizer(array(
                new YamlFileNormalizer(),
                new XmlFileNormalizer(),
                new SuddenAnnotationFileNormalizer(),
            )),
        );

        parent::__construct($normalizers);
    }
}
