<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer as CommonArrayNormalizer;

class ArrayNormalizer extends CommonArrayNormalizer
{
    public function __construct($normalizers = [])
    {
        $normalizers = $normalizers ?: [
            new ValueNormalizer(),
            new ExpressionNormalizer(),
        ];

        parent::__construct($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        $data = $data['key'];

        return parent::supports($data, $resource);
    }

    /**
     * @param mixed                        $data
     * @param \Yosmanyga\Resource\Resource $resource
     *
     * @return \Yosmanyga\Validation\Resource\Definition\ArrayDefinition
     */
    public function normalize($data, Resource $resource)
    {
        if (isset($data['value']['map'])) {
            $options = isset($data['value']['map']['options']) ? $data['value']['map']['options'] : [];
            $data['value']['map'] = $this->normalizer->normalize(
                [
                    'key'   => $data['value']['map']['validator'],
                    'value' => $options,
                ],
                $resource
            );
        }

        return $this->createDefinition($data['value']);
    }
}
