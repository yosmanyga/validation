<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;
use Yosmanyga\Validation\Validator\ValueValidator;

class ValueDefinition extends Definition implements ValidatedInterface
{
    /**
     * @var boolean
     */
    public $allowNull;

    /**
     * @var string
     */
    public $type;

    /**
     * @var mixed
     */
    public $eq;

    /**
     * @var mixed
     */
    public $neq;

    /**
     * @var mixed
     */
    public $iq;

    /**
     * @var mixed
     */
    public $niq;

    /**
     * @var mixed
     */
    public $gt;

    /**
     * @var mixed
     */
    public $ge;

    /**
     * @var mixed
     */
    public $lt;

    /**
     * @var mixed
     */
    public $le;

    /**
     * @var array
     */
    public $in;

    /**
     * @var array
     */
    public $nin;

    /**
     * @var array
     */
    public $messages;

    /**
     * @param boolean $allowNull
     * @param string  $type
     * @param mixed   $eq
     * @param mixed   $neq
     * @param mixed   $iq
     * @param mixed   $niq
     * @param mixed   $gt
     * @param mixed   $ge
     * @param mixed   $lt
     * @param mixed   $le
     * @param array   $in
     * @param array   $nin
     * @param array   $messages
     */
    public function __construct(
        $allowNull = null,
        $type = null,
        $eq = null,
        $neq = null,
        $iq = null,
        $niq = null,
        $gt = null,
        $ge = null,
        $lt = null,
        $le = null,
        $in = null,
        $nin = null,
        $messages = null
    )
    {
        $this->allowNull = $allowNull;
        $this->type = $type;
        $this->eq = $eq;
        $this->neq = $neq;
        $this->iq = $iq;
        $this->niq = $niq;
        $this->gt = $gt;
        $this->ge = $ge;
        $this->lt = $lt;
        $this->le = $le;
        $this->in = $in;
        $this->nin = $nin;
        $this->messages = $messages ?: array();
    }

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
