<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer as CommonValueNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer as YamlFileValueNormalizer;

class ValueNormalizer extends CommonValueNormalizer
{
    /**
     * @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer
     */
    private $yamlFileNormalizer;

    public function __construct($yamlFileNormalizer = null)
    {
        $this->yamlFileNormalizer = $yamlFileNormalizer ?: new YamlFileValueNormalizer();
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
