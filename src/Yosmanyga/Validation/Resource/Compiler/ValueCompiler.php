<?php

namespace Yosmanyga\Validation\Resource\Compiler;

use Yosmanyga\Resource\Compiler\CompilerInterface;
use Yosmanyga\Validation\Resource\Definition\ValueDefinition;
use Yosmanyga\Validation\Validator\ExceptionValidator;
use Yosmanyga\Validation\Validator\ScalarValidator;

class ValueCompiler implements CompilerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($definition)
    {
        if ($definition instanceof ValueDefinition) {
            return true;
        }

        return false;
    }

    /**
     * @param \Yosmanyga\Validation\Resource\Definition\ValueDefinition $definition
     *
     * @return \Yosmanyga\Validation\Validator\ScalarValidator
     */
    public function compile($definition)
    {
        $validator = $this->createValidator($definition);
        $validator->validate($definition);

        return new ScalarValidator($definition->export());
    }

    /**
     * @param \Yosmanyga\Validation\Resource\Definition\ValueDefinition $definition
     *
     * @return \Yosmanyga\Validation\Validator\ExceptionValidator
     */
    protected function createValidator($definition)
    {
        return new ExceptionValidator($definition->createValidator());
    }
}
