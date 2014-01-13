<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer as CommonArrayNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer as YamlFileArrayNormalizer;

class ArrayNormalizer extends CommonArrayNormalizer
{
    /**
     * @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer
     */
    private $yamlFileNormalizer;

    public function __construct($normalizers = array(), $yamlFileNormalizer = null)
    {
        $normalizers = $normalizers ?: array(
            new ValueNormalizer(),
            new ExpressionNormalizer()
        );

        parent::__construct($normalizers);

        $this->yamlFileNormalizer = $yamlFileNormalizer ?: new YamlFileArrayNormalizer();
    }

    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (false !== strrpos($data['key'], '\\')) {
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
