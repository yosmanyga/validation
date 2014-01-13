<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Normalizer\XmlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ExpressionValueValidator;
use Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer as CommonNormalizer;

class Normalizer extends CommonNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer
     */
    private $delegator;

    /**
     * @inheritdoc
     */
    public function __construct($normalizers = array())
    {
        $normalizers = $normalizers ?: array(
            new ValueNormalizer(),
            new ExpressionNormalizer(),
            new ArrayNormalizer(),
            new ObjectReferenceNormalizer()
        );

        $this->delegator = new XmlFileDelegatorNormalizer($normalizers);
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
        $validator = new ExceptionValidator(new ArrayValidator(array(
            'requiredKeys' => array('name'),
            'allowedKeys' => array('property'),
            'allowExtra' => false
        )));

        $validator->validate($data['value']);

        $definitions = array();
        if (isset($data['value']['property'])) {
            $definitions['properties'] = $this->normalizeProperties(
                $data['value']['property'],
                $resource
            );
        }

        return $this->createDefinition($data['value']['name'], $definitions);
    }

    protected function normalizeProperties($properties, $resource)
    {
        $validator = new ExceptionValidator(new ArrayValidator(array(
            'requiredKeys' => array('name', 'validator'),
            'allowExtra' => false
        )));

        if (!is_integer(key($properties))) {
            $properties = array($properties);
        }

        $definitions = array();
        foreach ($properties as $property) {
            $validator->validate($property);

            $definitions[$property['name']] = $this->normalizeValidators($property['validator'], $resource);
        }

        return $definitions;
    }

    protected function normalizeValidators($validators, $resource)
    {
        $validatorValidator = new ExceptionValidator(new ArrayValidator(array(
            'allowedKeys' => array('name', 'option'),
            'allowExtra' => false
        )));

        if (!is_integer(key($validators))) {
            $validators = array($validators);
        }

        $definitions = array();
        foreach ($validators as $validator) {
            $validatorValidator->validate($validator);

            $definitions[] = $this->delegator->normalize(
                array(
                    'value' => $validator
                ),
                $resource
            );
        }

        return $definitions;
    }
}
