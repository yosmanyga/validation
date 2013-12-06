<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Resource\Compiler\DelegatorCompiler;

class ObjectCompiler implements CompilerInterface
{
    /** @var \Yosmanyga\Resource\Compiler\DelegatorCompiler  */
    private $compiler;

    /**
     * @param \Yosmanyga\Resource\Compiler\DelegatorCompiler $compiler
     */
    public function __construct(DelegatorCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * @inheritdoc
     */
    public function supports($definition)
    {
        if ($definition instanceof ObjectDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Yosmanyga\Validation\Resource\Definition\ObjectDefinition $definition
     * @return \Yosmanyga\Validation\Validator\ObjectValidator
     */
    public function compile($definition)
    {
        $validators = array();
        /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition $definition */
        foreach ($definition->validators['properties'] as $property => $validatorDefinitions) {
            foreach ($validatorDefinitions as $validator) {
                $validators[$property][] = $this->compiler->compile($validator);
            }
        }

        return new ObjectValidator($validators);
    }
}
