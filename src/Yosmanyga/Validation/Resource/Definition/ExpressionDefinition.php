<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ScalarValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;

class ExpressionDefinition extends Definition implements ValidatedInterface
{
    public $expression;
    public $message;

    /**
     * {@inheritdoc}
     */
    public function createValidator()
    {
        return new ObjectValidator([
            'expression' => new ScalarValidator([
                'type' => 'string',
            ]),
            'message' => new ScalarValidator([
                'type' => 'string',
            ]),
        ]);
    }
}
