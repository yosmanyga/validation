<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;

class ValueNormalizer extends AbstractNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\DelegatorNormalizer
     */
    private $normalizer;

    /**
     * @param $normalizers \Yosmanyga\Resource\Normalizer\NormalizerInterface[]
     */
    public function __construct($normalizers = array())
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }

    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (isset($data['name']) && 'Value' == $data['name']) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed                                                     $data
     * @param  \Yosmanyga\Resource\Resource                              $resource
     * @return \Yosmanyga\Validation\Resource\Definition\ValueDefinition
     */
    public function normalize($data, Resource $resource)
    {
        $options = array();
        if (isset($data['option'])) {
            $options = $this->normalizeOptions($data['option']);
        }

        $definition = new ValueDefinition();
        $definition->import($options);

        return $definition;
    }

    public function setNormalizers($normalizers)
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }
}
