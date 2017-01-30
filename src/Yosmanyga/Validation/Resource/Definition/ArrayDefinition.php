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
     * @var bool
     */
    public $allowExtra;

    /**
     * @var array
     */
    public $messages;

    /**
     * @param array $requiredKeys
     * @param array $allowedKeys
     * @param array $map
     * @param array $deniedKeys
     * @param bool  $allowExtra
     * @param array $messages
     */
    public function __construct(
        $requiredKeys = null,
        $allowedKeys = null,
        $map = null,
        $deniedKeys = null,
        $allowExtra = null,
        $messages = null
    ) {
        $this->requiredKeys = $requiredKeys ?: [];
        $this->allowedKeys = $allowedKeys ?: [];
        $this->map = $map ?: [];
        $this->deniedKeys = $deniedKeys ?: [];
        $this->allowExtra = $allowExtra;
        $this->messages = $messages ?: [];
    }

    /**
     * {@inheritdoc}
     */
    public function createValidator()
    {
        return new ObjectValidator([
            'requiredKeys' => new ArrayValidator([
                'map'      => new ValueValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'requiredKeys values must be strings',
                ],
            ]),
            'allowedKeys' => new ArrayValidator([
                'map'      => new ValueValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'allowedKeys values must be strings',
                ],
            ]),
            'deniedKeys' => new ArrayValidator([
                'map'      => new ValueValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'deniedKeys values must be strings',
                ],
            ]),
            'allowExtra' => new ValueValidator([
                'allowNull' => true,
                'type'      => 'boolean',
            ]),
            'messages' => new ArrayValidator([
                'map'         => new ValueValidator(['type' => 'string']),
                'allowedKeys' => ['null', 'type', 'requiredKeys', 'deniedKeys', 'allowExtra'],
                'messages'    => [
                    'map' => 'messages values must be strings',
                ],
            ]),
        ]);
    }
}
