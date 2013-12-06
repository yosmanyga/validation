<?php

namespace Yosmanyga\Validation\Resource\Loader;

use Yosmanyga\Resource\Cacher\CacherInterface;
use Yosmanyga\Resource\Cacher\NullCacher;
use Yosmanyga\Resource\Loader\LoaderInterface;
use Yosmanyga\Resource\Normalizer\NormalizerInterface;
use Yosmanyga\Resource\Reader\Iterator\ReaderInterface;
use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;

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
    public function __construct(ReaderInterface $reader, NormalizerInterface $normalizer, ObjectCompiler $compiler, CacherInterface $cacher)
    {
        $this->reader = $reader;
        $this->normalizer = $normalizer;
        $this->compiler = $compiler;
        $this->cacher = $cacher ?: new NullCacher();
    }

    /**
     * @param $resource \Yosmanyga\Resource\Resource
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    public function load($resource)
    {
        if ($this->cacher->check($resource)) {
            return $this->cacher->retrieve($resource);
        }

        $this->reader->open($resource);

        $validators = array();
        while ($data = $this->reader->current()) {
            /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition $definition */
            $definition = $this->normalizer->normalize($data, $resource);

            $validators[$definition->class] = $this->compiler->compile($definition);

            $this->reader->next();
        }

        $this->cacher->store($validators, $resource);

        return $validators;
    }
}
