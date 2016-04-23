<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ValueValidator;

class ValueDefinition extends Definition implements ValidatedInterface
{
    public $allowNull;
    public $type;
    public $eq;
    public $neq;
    public $iq;
    public $niq;
    public $gt;
    public $ge;
    public $lt;
    public $le;
    public $in;
    public $nin;
    public $messages;

    /**
     * {@inheritdoc}
     */
    public function createValidator()
    {
        return new ObjectValidator(array(
            'allowNull' => new ValueValidator(array(
                'type' => 'boolean',
            )),
            'type' => new ValueValidator(array(
                'type' => 'string',
            )),
            'in' => new ValueValidator(array(
                'type' => 'array',
            )),
            'nin' => new ValueValidator(array(
                'type' => 'array',
            )),
            'messages' => new ArrayValidator(array(
                'allowedKeys' => array('null', 'type', 'eq', 'neq', 'iq', 'niq', 'gt', 'ge', 'lt', 'le', 'in', 'nin'),
                'map' => new ValueValidator(array('type' => 'string')),
                'messages' => array(
                    'map' => 'messages values must be strings',
                ),
            )),
        ));
    }
}
