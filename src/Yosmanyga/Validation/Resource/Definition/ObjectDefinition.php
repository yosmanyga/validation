<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ScalarValidator;

class ObjectDefinition extends Definition implements ValidatedInterface
{
    public $class;
    public $validators;

    /**
     * {@inheritdoc}
     */
    public function createValidator()
    {
        return new ObjectValidator([
            'class' => new ScalarValidator([
                'type' => 'string',
            ]),
        ]);
    }
}
