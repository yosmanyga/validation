<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ExpressionNormalizer as CommonExpressionNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer as YamlFileExpressionNormalizer;

class ExpressionNormalizer extends CommonExpressionNormalizer
{
    /**
     * @var \Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer
     */
    private $yamlFileNormalizer;

    public function __construct($yamlFileNormalizer = null)
    {
        $this->yamlFileNormalizer = $yamlFileNormalizer ?: new YamlFileExpressionNormalizer();
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
