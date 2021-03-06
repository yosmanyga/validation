<?php

namespace Yosmanyga\Validation\Resource\Normalizer\YamlFile;

use Yosmanyga\Resource\Normalizer\YamlFileDelegatorNormalizer;
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
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface $normalizers
     */
    public function __construct($normalizers = null)
    {
        $normalizers = $normalizers ?: [
            new ValueNormalizer(),
            new ExpressionNormalizer(),
            new ArrayNormalizer(),
            new ObjectReferenceNormalizer(),
        ];

        $this->delegator = new YamlFileDelegatorNormalizer($normalizers);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, Resource $resource)
    {
        return $this->delegator->supports($data, $resource);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data, Resource $resource)
    {
        $validator = new ExceptionValidator(new ArrayValidator([
            'optionalKeys' => ['properties'],
            'allowExtra'   => false,
        ]));
        $validator->validate($data['value']);

        $definitions = [];
        if (isset($data['value']['properties'])) {
            $definitions['properties'] = $this->normalizeProperties(
                $data['value']['properties'],
                $resource
            );
        }

        return $this->createDefinition($data['key'], $definitions);
    }

    protected function normalizeProperties($properties, $resource)
    {
        $validatorValidator = new ExceptionValidator(new ArrayValidator([
            'requiredKeys'  => ['validator'],
            'optionalKeys'  => ['options'],
            'allowExtra'    => false,
        ]));

        $definitions = [];
        foreach ($properties as $property => $validators) {
            foreach ($validators as $id => $validator) {
                if (!is_int($id)) {
                    $validator = [
                        'validator' => $id,
                        'options'   => $validator,
                    ];
                }

                $validatorValidator->validate($validator);

                $definitions[$property][] = $this->delegator->normalize(
                    [
                        'key'   => $validator['validator'],
                        'value' => $validator['options'],
                    ],
                    $resource
                );
            }
        }

        return $definitions;
    }
}
