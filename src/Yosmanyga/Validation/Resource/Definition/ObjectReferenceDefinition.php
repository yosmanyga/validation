<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ScalarValidator;

class ObjectReferenceDefinition extends Definition
{
    public $class;

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
