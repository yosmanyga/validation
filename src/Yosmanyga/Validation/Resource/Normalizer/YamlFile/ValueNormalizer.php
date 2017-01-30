<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ValueNormalizer as CommonValueNormalizer;

class ValueNormalizer extends CommonValueNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        $data = $data['key'];

        return parent::supports($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, Resource $resource)
    {
        return $this->createDefinition($data['value']);
    }
}
