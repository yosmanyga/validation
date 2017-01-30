<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Yosmanyga\Resource\Normalizer\XmlFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer as CommonNormalizer;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class Normalizer extends CommonNormalizer
{
    /**
     * @var \Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer
     */
    private $delegator;

    /**
     * {@inheritdoc}
     */
    public function __construct($normalizers = [])
    {
        $normalizers = $normalizers ?: [
            new ValueNormalizer(),
            new ExpressionNormalizer(),
            new ArrayNormalizer(),
            new ObjectReferenceNormalizer(),
        ];

        $this->delegator = new XmlFileDelegatorNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        return $this->delegator->supports($data, $resource);
    }

    /**
     * @param mixed                        $data
     * @param \Yosmanyga\Resource\Resource $resource
     *
     * @return mixed
     */
    public function normalize($data, Resource $resource)
    {
        $validator = new ExceptionValidator(new ArrayValidator([
            'requiredKeys' => ['name'],
            'allowedKeys'  => ['property'],
            'allowExtra'   => false,
        ]));

        $validator->validate($data['value']);

        $definitions = [];
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
        $validator = new ExceptionValidator(new ArrayValidator([
            'requiredKeys' => ['name', 'validator'],
            'allowExtra'   => false,
        ]));

        if (!is_int(key($properties))) {
            $properties = [$properties];
        }

        $definitions = [];
        foreach ($properties as $property) {
            $validator->validate($property);

            $definitions[$property['name']] = $this->normalizeValidators($property['validator'], $resource);
        }

        return $definitions;
    }

    protected function normalizeValidators($validators, $resource)
    {
        $validatorValidator = new ExceptionValidator(new ArrayValidator([
            'allowedKeys' => ['name', 'option'],
            'allowExtra'  => false,
        ]));

        if (!is_int(key($validators))) {
            $validators = [$validators];
        }

        $definitions = [];
        foreach ($validators as $validator) {
            $validatorValidator->validate($validator);

            $definitions[] = $this->delegator->normalize(
                [
                    'value' => $validator,
                ],
                $resource
            );
        }

        return $definitions;
    }
}
