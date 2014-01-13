<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Normalizer\SuddenAnnotationFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer as CommonNormalizer;

class Normalizer extends CommonNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer
     */
    protected $delegator;

    /**
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface $normalizers
     */
    public function __construct($normalizers = null)
    {
        $normalizers = $normalizers ?: array(
            new ValueNormalizer(),
            new ExpressionNormalizer(),
            new ArrayNormalizer(array(
                new ValueNormalizer(),
                new ExpressionNormalizer()
            )),
            new ObjectReferenceNormalizer()
        );

        $this->delegator = new SuddenAnnotationFileDelegatorNormalizer($normalizers);
    }

    /**
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        return $this->delegator->supports($data, $resource);
    }

    /**
     * @param  mixed                        $data
     * @param  \Yosmanyga\Resource\Resource $resource
     * @return mixed
     */
    public function normalize($data, Resource $resource)
    {
        $class = '';
        $definitions = array();
        foreach ($data['value'] as $validator) {
            $class = $validator['metadata']['class'];
            if (isset($validator['property'])) {
                $definitions['properties'][$validator['property']][] = $this->delegator->normalize($validator, $resource);
            }
        }

        return $this->createDefinition($class, $definitions);
    }
}
