<?php

namespace Yosmanyga\Validation\Resource\Loader;

use Yosmanyga\Resource\Cacher\Cacher;
use Yosmanyga\Resource\Cacher\CacherInterface;
use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Resource\Loader\LoaderInterface;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Reader\Iterator\DelegatorReader;
use Yosmanyga\Resource\Reader\Iterator\ReaderInterface;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\Normalizer;

class Loader implements LoaderInterface
{
    /**
     * @var \Yosmanyga\Resource\Reader\Iterator\ReaderInterface
     */
    private $reader;

    /**
     * @var \Yosmanyga\Resource\Normalizer\NormalizerInterface
     */
    private $normalizer;

    /**
     * @var \Yosmanyga\Resource\Compiler\CompilerInterface
     */
    private $compiler;

    /**
     * @var \Yosmanyga\Resource\Cacher\CacherInterface
     */
    private $cacher;

    /**
     * @param \Yosmanyga\Resource\Reader\Iterator\ReaderInterface $reader
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface  $normalizer
     * @param \Yosmanyga\Resource\Compiler\CompilerInterface      $compiler
     * @param \Yosmanyga\Resource\Cacher\CacherInterface          $cacher
     */
    public function __construct(
        ReaderInterface $reader = null,
        NormalizerInterface $normalizer = null,
        CompilerInterface $compiler = null,
        CacherInterface $cacher = null)
    {
        $this->reader = $reader ?: new DelegatorReader();
        $this->normalizer = $normalizer ?: new Normalizer();
        $this->compiler = $compiler ?: new ObjectCompiler();
        $this->cacher = $cacher ?: new Cacher();
    }

    /**
     * @param \Yosmanyga\Resource\Resource $resource
     *
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    public function load($resource)
    {
        if ($this->cacher->check($resource)) {
            return $this->cacher->retrieve($resource);
        }

        $this->reader->open($resource);

        /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition[] $definitions */
        $definitions = [];
        /** @var \Yosmanyga\Validation\Validator\ObjectValidator[] $validators */
        $validators = [];
        while ($data = $this->reader->current()) {
            /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition $definition */
            $definition = $this->normalizer->normalize($data, $resource);

            $definitions[$definition->class] = $definition;
            $validators[$definition->class] = $this->compiler->compile($definition);

            $this->reader->next();
        }

        $validators = $this->fillObjectValidators($definitions, $validators);

        $this->cacher->store($validators, $resource);

        return $validators;
    }

    /**
     * @param \Yosmanyga\Validation\Resource\Definition\ObjectDefinition[] $definitions
     * @param \Yosmanyga\Validation\Validator\ObjectValidator[]            $validators
     *
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    protected function fillObjectValidators($definitions, $validators)
    {
        foreach ($definitions as $class => $definition) {
            foreach ($definition->validators['properties'] as $property => $validatorsDefinitions) {
                foreach ($validatorsDefinitions as $validatorDefinition) {
                    if ($validatorDefinition instanceof ObjectReferenceDefinition) {
                        $validator = $validators[$class];
                        $propertyValidators = $validator->getValidators();
                        $propertyValidators[$property] = $validators[$validatorDefinition->class];
                        $validator->setValidators($propertyValidators);
                    }
                }
            }
        }

        return $validators;
    }
}
