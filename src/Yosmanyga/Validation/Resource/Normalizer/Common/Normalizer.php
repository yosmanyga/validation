<?php

namespace Yosmanyga\Validation\Resource\Normalizer\Common;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;

abstract class Normalizer implements NormalizerInterface
{
    /**
     * @param  array                                                      $class
     * @param  \Yosmanyga\Resource\Definition\DefinitionInterface[]       $validators
     * @return \Yosmanyga\Validation\Resource\Definition\ObjectDefinition
     */
    protected function createDefinition($class, $validators)
    {
        $definition = new ObjectDefinition();
        $definition->class = $class;
        $definition->validators = $validators;

        return $definition;
    }
}
