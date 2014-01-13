<?php

namespace Yosmanyga\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

abstract class ValueNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if ('Value' == $data) {
            return true;
        }

        return false;
    }

    /**
     * @param  array                                                     $data
     * @return \Yosmanyga\Validation\Resource\Definition\ValueDefinition
     */
    protected function createDefinition($data)
    {
        $definition = new ValueDefinition();
        $definition->import($data);

        return $definition;
    }
}
