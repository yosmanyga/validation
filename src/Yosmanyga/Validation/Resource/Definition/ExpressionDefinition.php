<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ValueValidator;

class ExpressionDefinition extends Definition implements ValidatedInterface
{
    public $expression;
    public $message;

    /**
     * @inheritdoc
     */
    public function createValidator()
    {
        return new ObjectValidator(array(
            'expression' => new ValueValidator(array(
                'type' => 'string'
            )),
            'message' => new ValueValidator(array(
                'type' => 'string'
            ))
        ));
    }
}
