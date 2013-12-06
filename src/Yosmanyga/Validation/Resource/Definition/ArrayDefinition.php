<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ValueValidator;

class ArrayDefinition extends Definition implements ValidatedInterface
{
    public $requiredKeys;
    public $allowedKeys;
    public $map;
    public $deniedKeys;
    public $allowExtra;
    public $messages;

    /**
     * @inheritdoc
     */
    public function createValidator()
    {
        return new ObjectValidator(array(
            'requiredKeys' => new ArrayValidator(array(
                'map' => function ($e) {
                    return is_string($e);
                },
                'messages' => array(
                    'map' => 'requiredKeys values must be strings'
                )
            )),
            'allowedKeys' => new ArrayValidator(array(
                'map' => function ($e) {
                    return is_string($e);
                },
                'messages' => array(
                    'map' => 'allowedKeys values must be strings'
                )
            )),
            'deniedKeys' => new ArrayValidator(array(
                'map' => function ($e) {
                    return is_string($e);
                },
                'messages' => array(
                    'map' => 'deniedKeys values must be strings'
                )
            )),
            'allowExtra' => new ValueValidator(array(
                'type' => 'boolean'
            )),
            'messages' => new ArrayValidator(array(
                'map' => function ($e) {
                    return is_string($e);
                },
                'allowedKeys' => array('null', 'type', 'requiredKeys', 'deniedKeys', 'allowExtra'),
                'messages' => array(
                    'map' => 'messages values must be strings'
                ),
            ))
        ));
    }
}
