<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

class ValueNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (isset($data['key']) && 'Value' == $data['key']) {
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
        $definition = new ValueDefinition();
        $definition->import($data['value']);

        return $definition;
    }
}
