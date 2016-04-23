<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\ArrayNormalizer as CommonArrayNormalizer;
use Yosmanyga\Resource\Util\XmlKit;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;

class ArrayNormalizer extends CommonArrayNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Util\XmlKit
     */
    private $xmlKit;

    public function __construct($normalizers = array(), $xmlKit = null)
    {
        $normalizers = $normalizers ?: array(
            new ValueNormalizer(),
            new ExpressionNormalizer(),
        );

        parent::__construct($normalizers);

        $this->xmlKit = $xmlKit ?: new XmlKit();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        $data = $data['value']['name'];

        return parent::supports($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, Resource $resource)
    {
        if (isset($data['value']['option'])) {
            $data['value'] = $this->xmlKit->extractContent($data['value']['option']);
        }

        if (isset($data['value']['map'])) {
            $data['value']['map'] = $this->normalizer->normalize(
                array(
                    'value' => $data['value']['map'],
                ),
                $resource
            );
        }

        $definition = new ArrayDefinition();
        $definition->import($data['value']);

        return $definition;
    }
}
