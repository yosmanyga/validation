<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;

class ExpressionNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (isset($data['key']) && 'Expression' == $data['key']) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed                                                     $data
     * @param  \Yosmanyga\Resource\Resource                              $resource
     * @return \Yosmanyga\Validation\Resource\Definition\ExpressionDefinition
     */
    public function normalize($data, Resource $resource)
    {
        $definition = new ExpressionDefinition();
        $definition->import($data['value']);

        return $definition;
    }
}
