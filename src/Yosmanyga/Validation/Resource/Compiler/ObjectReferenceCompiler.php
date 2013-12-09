<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class ObjectReferenceCompiler implements CompilerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($definition)
    {
        if ($definition instanceof ObjectReferenceDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition $definition
     * @return null
     */
    public function compile($definition)
    {
        $definitionValidator = new ExceptionValidator($definition->createValidator());
        $definitionValidator->validate($definition);

        return null;
    }
}