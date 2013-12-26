<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Resource\Normalizer\YamlFileNormalizer;

class Normalizer extends YamlFileNormalizer
{
    /**
     * @inheritdoc
     */
    public function __construct($normalizers = array())
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

        parent::__construct($normalizers);
    }

    /**
     * @param  mixed                        $data
     * @param  \Yosmanyga\Resource\Resource $resource
     * @return mixed
     */
    public function normalize($data, Resource $resource)
    {
        $dataValidator = new ExceptionValidator(new ArrayValidator(array(
            'allowedKeys' => array('properties'),
            'allowExtra' => false
        )));
        $dataValidator->validate($data['value']);

        $class = $data['key'];
        $validatorDefinitions = array(
            'properties' => array()
        );
        if (isset($data['value']['properties'])) {
            $validatorValidator = new ExceptionValidator(new ArrayValidator(array(
                'requiredKeys' => array('validator'),
                'allowedKeys' => array('options'),
                'allowExtra' => false
            )));

            foreach ($data['value']['properties'] as $property => $validators) {
                foreach ($validators as $id => $options) {
                    try {
                        $validatorDefinitions['properties'][$property][] = $this->normalizer->normalize(array('key' => $id, 'value' => $options), $resource);
                    } catch (\RuntimeException $e) {
                        if (isset($options['validator'])) {
                            $validatorValidator->validate($options);

                            $validatorDefinitions['properties'][$property][] = $this->normalizer->normalize(array('key' => $options['validator'], 'value' => $options['options']), $resource);
                        }
                    }
                }
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
