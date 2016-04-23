<?php

namespace Yosmanyga\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;

abstract class ArrayNormalizer implements NormalizerInterface
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\DelegatorNormalizer
     */
    protected $normalizer;

    /**
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface[] $normalizers
     */
    public function __construct($normalizers = array())
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        if ('Array' == $data) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     *
     * @return \Yosmanyga\Validation\Resource\Definition\ExpressionDefinition
     */
    protected function createDefinition($data)
    {
        $definition = new ArrayDefinition();
        $definition->import($data);

        return $definition;
    }
}
