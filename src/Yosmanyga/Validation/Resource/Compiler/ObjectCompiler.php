<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\DelegatorCompiler;
use Yosmanyga\Validation\Resource\Definition\ObjectDefinition;
use Yosmanyga\Validation\Validator\ObjectValidator;

class ObjectCompiler extends DelegatorCompiler
{
    /**
     * @param \Yosmanyga\Resource\Compiler\CompilerInterface[] $compilers
     */
    public function __construct($compilers = null)
    {
        $compilers = $compilers ?: array(
            new ValueCompiler(),
            new ExpressionCompiler(),
            new ArrayCompiler(array(
                new ValueCompiler(),
                new ExpressionCompiler()
            )),
            new ObjectReferenceCompiler()
        );

        parent::__construct($compilers);
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

        if (isset($definition->validators)) {
            /** @var \Yosmanyga\Validation\Resource\Definition\ObjectDefinition $definition */
            foreach ($definition->validators['properties'] as $property => $validatorDefinitions) {
                foreach ($validatorDefinitions as $validator) {
                    $validators[$property][] = $this->compile($validator);
                }
            }
        }

        return new ObjectValidator($validators);
    }
}
