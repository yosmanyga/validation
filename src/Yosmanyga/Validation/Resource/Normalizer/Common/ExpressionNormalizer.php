<?php

namespace Yosmanyga\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;

abstract class ExpressionNormalizer implements NormalizerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if ('Expression' == $data) {
            return true;
        }

        return false;
    }

    /**
     * @param  array                                                          $data
     * @return \Yosmanyga\Validation\Resource\Definition\ExpressionDefinition
     */
    protected function createDefinition($data)
    {
        $definition = new ExpressionDefinition();
        $definition->import($data);

        return $definition;
    }
}
