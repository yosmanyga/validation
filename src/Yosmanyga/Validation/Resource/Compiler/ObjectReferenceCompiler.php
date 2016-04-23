<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;

class ObjectReferenceCompiler implements CompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($definition)
    {
        if ($definition instanceof ObjectReferenceDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param \Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition $definition
     */
    public function compile($definition)
    {
        $validator = $this->createValidator($definition);
        $validator->validate($definition);

        return;
    }

    /**
     * @param \Yosmanyga\Validation\Resource\Definition\ObjectReferenceDefinition $definition
     *
     * @return \Yosmanyga\Validation\Validator\ExceptionValidator
     */
    protected function createValidator($definition)
    {
        return new ExceptionValidator($definition->createValidator());
    }
}
