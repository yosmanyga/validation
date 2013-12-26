<?php

namespace Yosmanyga\Validation\Resource\Loader;

use Yosmanyga\Resource\Cacher\CacherInterface;
use Yosmanyga\Resource\Cacher\NullCacher;
use Yosmanyga\Resource\Loader\LoaderInterface;
use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
use Yosmanyga\Resource\Normalizer\DirectoryNormalizer;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Reader\Iterator\DelegatorReader;
use Yosmanyga\Resource\Reader\Iterator\ReaderInterface;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer as YamlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer as XmlFileNormalizer;
use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer as SuddenAnnotationFileNormalizer;

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
     * @var \Yosmanyga\Validation\Resource\Compiler\ObjectCompiler
     */
    private $compiler;

    /**
     * @var \Yosmanyga\Resource\Cacher\CacherInterface
     */
    private $cacher;

    /**
     * @param \Yosmanyga\Resource\Reader\Iterator\ReaderInterface       $reader
     * @param \Yosmanyga\Resource\Normalizer\NormalizerInterface        $normalizer
     * @param \Yosmanyga\Validation\Resource\Compiler\ObjectCompiler    $compiler
     * @param \Yosmanyga\Resource\Cacher\CacherInterface                $cacher
     */
    public function __construct(
        ReaderInterface $reader = null,
        NormalizerInterface $normalizer = null,
        ObjectCompiler $compiler = null,
        CacherInterface $cacher = null)
    {
        $this->reader = $reader ?: new DelegatorReader();
        $this->normalizer = $normalizer ?: new DelegatorNormalizer(array(
            new YamlFileNormalizer(),
            new XmlFileNormalizer,
            new SuddenAnnotationFileNormalizer(),
            new DirectoryNormalizer(array(
                new YamlFileNormalizer(),
                new XmlFileNormalizer,
                new SuddenAnnotationFileNormalizer()
            ))
        ));
        $this->compiler = $compiler ?: new ObjectCompiler();
        $this->cacher = $cacher ?: new NullCacher();
    }

    /**
     * @param \Yosmanyga\Resource\Resource $resource
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    public function load($resource)
    {
        if ($this->cacher->check($resource)) {
            return $this->cacher->retrieve($resource);
        }

        $this->reader->open($resource);

        /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition[] $definitions */
        $definitions = array();
        /** @var \Yosmanyga\Validation\Validator\ObjectValidator[] $validators */
        $validators = array();
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
     * @param \Yosmanyga\Validation\Validator\ObjectValidator[] $validators
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    private function fillObjectValidators($definitions, $validators)
    {
        foreach ($definitions as $class => $definition) {
            foreach ($definition->validators['properties'] as $property => $validatorsDefinitions) {
                foreach ($validatorsDefinitions as $i => $validatorDefinition) {
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
