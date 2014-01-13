<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ArrayDefinition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Resource\Compiler\DelegatorCompiler;

class ArrayCompiler implements CompilerInterface
{
    /**
     * @var \Yosmanyga\Resource\Compiler\DelegatorCompiler
     */
    private $compiler;

    /**
     * @param $compilers \Yosmanyga\Resource\Compiler\CompilerInterface[]
     */
    public function __construct($compilers = array())
    {
        $compilers = $compilers ?: array(
            new ValueCompiler(),
            new ExpressionCompiler()
        );

        $this->compiler = new DelegatorCompiler($compilers);
    }
    
    /**
     * @inheritdoc
     */
    public function supports($definition)
    {
        if ($definition instanceof ArrayDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Yosmanyga\Validation\Resource\Definition\ArrayDefinition $definition
     * @return \Yosmanyga\Validation\Validator\ArrayValidator
     */
    public function compile($definition)
    {
        $validator = new ExceptionValidator($definition->createValidator());
        $validator->validate($definition);

        // Try to compile map option
        // Just works if map is a validator
        try {
            $definition->map = $this->compiler->compile($definition->map);
        } catch (\RuntimeException $e) {
            // Ignore it if fails
            // Map could be a closure or anything else callable
        }

        return new ArrayValidator($definition->export());
    }
}
