<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;

class ValueNormalizer extends AbstractNormalizer
{
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
}
