<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ExpressionDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ExpressionPropertyValidator;

class ExpressionCompiler implements CompilerInterface
{
    /**
     * @inheritdoc
     */
    public function supports($definition)
    {
        if ($definition instanceof ExpressionDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param  \Yosmanyga\Validation\Resource\Definition\ExpressionDefinition $definition
     * @return \Yosmanyga\Validation\Validator\ExpressionPropertyValidator
     */
    public function compile($definition)
    {
        $definitionValidator = new ExceptionValidator($definition->createValidator());
        $definitionValidator->validate($definition);

        $options = $definition->export();
        $expression = $options['expression'];
        unset($options['expression']);

        return new ExpressionPropertyValidator($expression, $options);
    }
}
