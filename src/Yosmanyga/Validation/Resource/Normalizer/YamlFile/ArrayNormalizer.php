<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;

class ArrayNormalizer implements NormalizerInterface
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
        if (isset($data['key']) && 'Array' == $data['key']) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed                                                     $data
     * @param  \Yosmanyga\Resource\Resource                              $resource
     * @return \Yosmanyga\Validation\Resource\Definition\ArrayDefinition
     */
    public function normalize($data, Resource $resource)
    {
        $definition = new ArrayDefinition();

        if (isset($data['value']['map'])) {
            try {
                $options = isset($data['value']['map']['options']) ? $data['value']['map']['options'] : array();
                $map = $this->normalizer->normalize(
                    array(
                        'key' => $data['value']['map']['validator'],
                        'value' => $options
                    ),
                    $resource
                );
            } catch (\RuntimeException $e) {
                $map = $data['value']['map']['validator'];
            }

            $data['value']['map'] = $map;
        }

        $definition->import($data['value']);

        return $definition;
    }

    public function setNormalizers($normalizers)
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }
}
