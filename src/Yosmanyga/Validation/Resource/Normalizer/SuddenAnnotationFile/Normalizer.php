<?php

namespace Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile;

use Yosmanyga\Resource\Normalizer\SuddenAnnotationFileDelegatorNormalizer;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Resource\Normalizer\Common\Normalizer as CommonNormalizer;

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
        $this->validateData($data['value']);

        $class = '';
        $validatorDefinitions = array();
        foreach ($data['value'] as $validator) {
            $class = $validator['metadata']['class'];
            if (isset($validator['property'])) {
                $validatorDefinitions['properties'][$validator['property']][] = $this->delegator->normalize($validator, $resource);
            }
        }

        $definition = new ObjectDefinition();
        $definition->class = $class;
        $definition->validators = $validatorDefinitions;

        return $definition;
    }

    private function validateData($data)
    {
        $dataValidator = new ExceptionValidator(new ArrayValidator());

        return $dataValidator->validate($data);
    }
}
