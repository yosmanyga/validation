<?php

namespace Yosmanyga\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;

abstract class ObjectReferenceNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        if ('Object' == $data) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     *
     * @return \Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition
     */
    protected function createDefinition($data)
    {
        $definition = new ObjectReferenceDefinition();
        $definition->import($data);

        return $definition;
    }
}
