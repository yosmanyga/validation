<?php

namespace Yosmanyga\Validation\Resource\Normalizer\XmlFile;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Resource;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ExpressionValueValidator;

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
     * @inheritdoc
     */
    public function supports($data, Resource $resource)
    {
        if ($resource->hasType('type')) {
            if ('xml' == $resource->getType()) {
                return true;
            }

            return false;
        }

        if ($resource->hasMetadata('file') && in_array(pathinfo($resource->getMetadata('file'), PATHINFO_EXTENSION), array('xml'))) {
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
        $dataValidator = new ExceptionValidator(new ArrayValidator(array(
            'requiredKeys' => array('name'),
            'allowedKeys' => array('property'),
            'allowExtra' => false
        )));
        $dataValidator->validate($data['value']);

        $class = $data['value']['name'];
        $validatorDefinitions = array(
            'properties' => array()
        );
        if (isset($data['value']['property'])) {
            $expressionLanguage = new ExpressionLanguage();
            $expressionLanguage->register('isset', function ($variable, $key) {
                return sprintf('isset(%s["%s"])', $variable, $key);
            }, function (array $values, $variable, $key) {
                return isset($variable[$key]);
            });
            $propertyValidator = new ExceptionValidator(new ExpressionValueValidator(
                'isset(value, "validator") or isset(value, "validators")',
                array(),
                $expressionLanguage
            ));

            $validatorValidator = new ExceptionValidator(new ArrayValidator(array(
                'requiredKeys' => array('name'),
                'allowedKeys' => array('option'),
                'allowExtra' => false
            )));

            foreach ($data['value']['property'] as $property) {
                $propertyValidator->validate($property);

                $validators = array();
                if (isset($property['validator'])) {
                    $validators = array($property['validator']);
                } elseif (isset($property['validators'])) {
                    $validators = $property['validators']['validator'];
                }

                foreach ($validators as $validator) {
                    $validatorValidator->validate($validator);

                    $validatorDefinitions['properties'][$property['name']][] = $this->normalizer->normalize($validator, $resource);
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
