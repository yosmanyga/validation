<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ValueValidator;

class ArrayDefinition extends Definition implements ValidatedInterface
{
    /**
     * @var array
     */
    public $requiredKeys;

    /**
     * @var array
     */
    public $allowedKeys;

    /**
     * @var array
     */
    public $map;

    /**
     * @var array
     */
    public $deniedKeys;

    /**
     * @var boolean
     */
    public $allowExtra;

    /**
     * @var array
     */
    public $messages;

    /**
     * @param array   $requiredKeys
     * @param array   $allowedKeys
     * @param array   $map
     * @param array   $deniedKeys
     * @param boolean $allowExtra
     * @param array   $messages
     */
    public function __construct(
        $requiredKeys = null,
        $allowedKeys = null,
        $map = null,
        $deniedKeys = null,
        $allowExtra = null,
        $messages = null
    )
    {
        $this->requiredKeys = $requiredKeys ?: array();
        $this->allowedKeys = $allowedKeys ?: array();
        $this->map = $map ?: array();
        $this->deniedKeys = $deniedKeys ?: array();
        $this->allowExtra = $allowExtra;
        $this->messages = $messages ?: array();;
    }


    /**
     * {@inheritdoc}
     */
    public function createValidator()
    {
        return new ObjectValidator(array(
            'requiredKeys' => new ArrayValidator(array(
                'map' => new ValueValidator(array('type' => 'string')),
                'messages' => array(
                    'map' => 'requiredKeys values must be strings',
                ),
            )),
            'allowedKeys' => new ArrayValidator(array(
                'map' => new ValueValidator(array('type' => 'string')),
                'messages' => array(
                    'map' => 'allowedKeys values must be strings',
                ),
            )),
            'deniedKeys' => new ArrayValidator(array(
                'map' => new ValueValidator(array('type' => 'string')),
                'messages' => array(
                    'map' => 'deniedKeys values must be strings',
                ),
            )),
            'allowExtra' => new ValueValidator(array(
                'allowNull' => true,
                'type' => 'boolean',
            )),
            'messages' => new ArrayValidator(array(
                'map' => new ValueValidator(array('type' => 'string')),
                'allowedKeys' => array('null', 'type', 'requiredKeys', 'deniedKeys', 'allowExtra'),
                'messages' => array(
                    'map' => 'messages values must be strings',
                ),
            )),
        ));
    }
}
