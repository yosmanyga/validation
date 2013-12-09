<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class Normalizer implements NormalizerInterface
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\DelegatorNormalizer
     */
    private $normalizer;

    /**
     * @param $normalizers \Yosmanyga\Resource\Normalizer\NormalizerInterface[]
     */
    public function __construct($normalizers = array())
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }

    /**
     * @inherit
     */
    public function supports($data, Resource $resource)
    {
        if ($resource->hasType('type') && 'annotation' == $resource->getType()) {
            return true;
        }

        return false;
    }

    /**
     * @param  mixed                        $data
     * @param  \Yosmanyga\Resource\Resource $resource
     * @return mixed
     */
    public function normalize($data, Resource $resource)
    {
        $validator = new ExceptionValidator(new ArrayValidator());
        $validator->validate($data['value']);

        $class = '';
        $validatorDefinitions = array(
            'properties' => array()
        );
        foreach ($data['value'] as $validator) {
            $class = $validator['metadata']['class'];
            if (isset($validator['property'])) {
                $validatorDefinitions['properties'][$validator['property']][] = $this->normalizer->normalize($validator, $resource);
            }
        }

        $definition = new ObjectDefinition();
        $definition->class = $class;
        $definition->validators = $validatorDefinitions;

        return $definition;
    }

    /**
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface[] $normalizers
     */
    public function setNormalizers($normalizers)
    {
        $this->normalizer = new DelegatorNormalizer($normalizers);
    }
}