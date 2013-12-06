<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;

class ExpressionNormalizer extends AbstractNormalizer
{
    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if (isset($data['name']) && 'Expression' == $data['name']) {
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
        $options = array();
        if (isset($data['option'])) {
            $options = $this->normalizeOptions($data['option']);
        }

        $definition = new ExpressionDefinition();
        $definition->import($options);

        return $definition;
    }
}
