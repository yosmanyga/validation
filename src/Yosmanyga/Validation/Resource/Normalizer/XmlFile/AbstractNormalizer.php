<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Normalizer\NormalizerInterface;

abstract class AbstractNormalizer implements NormalizerInterface
{
    /**
     * @param  array $options
     * @return array
     */
    protected function normalizeOptions($options)
    {
        if (isset($options['name'])) {
            $options = array($options);
        }

        $normalizedOptions = array();
        foreach ($options as $option) {
            $name = $option['name'];
            unset($option['name']);
            $normalizedOptions[$name] = current($option);
        }

        return $normalizedOptions;
    }
}
