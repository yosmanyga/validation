<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ObjectReferenceNormalizer as CommonObjectReferenceNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer as YamlFileObjectReferenceNormalizer;

class ObjectReferenceNormalizer extends CommonObjectReferenceNormalizer
{
    /**
     * @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\ObjectReferenceNormalizer
     */
    private $yamlFileNormalizer;

    public function __construct($yamlFileNormalizer = null)
    {
        $this->yamlFileNormalizer = $yamlFileNormalizer ?: new YamlFileObjectReferenceNormalizer();
    }

    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (strrpos($data['key'], '\\') !== false) {
            $data = substr($data['key'], strrpos($data['key'], '\\') + 1);
        } else {
            $data = $data['key'];
        }

        return parent::supports($data, $resource);
    }

    /**
     * @inheritdoc
     */
    public function normalize($data, Resource $resource)
    {
        return $this->yamlFileNormalizer->normalize($data, $resource);
    }
}
