<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ValueValidator;

class ValueCompiler implements CompilerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($definition)
    {
        if ($definition instanceof ValueDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Yosmanyga\Validation\Resource\Definition\ValueDefinition $definition
     * @return \Yosmanyga\Validation\Validator\ValueValidator
     */
    public function compile($definition)
    {
        $definitionValidator = new ExceptionValidator($definition->createValidator());
        $definitionValidator->validate($definition);

        return new ValueValidator($definition->export());
    }
}
