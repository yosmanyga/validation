<?php

namespace Yosmanyga\Validation\Resource\Definition;

use Yosmanyga\Resource\Definition\Definition;
use Yosmanyga\Validation\Validator\ArrayValidator;
use Yosmanyga\Validation\Validator\ObjectValidator;
use Yosmanyga\Validation\Validator\ScalarValidator;
use Yosmanyga\Validation\Validator\ValidatedInterface;

class ArrayDefinition extends Definition implements ValidatedInterface
{
    /**
     * @var array
     */
    public $requiredKeys;

    /**
     * @var array
     */
    public $optionalKeys;

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
     * @param array $optionalKeys
     * @param array $map
     * @param array $deniedKeys
     * @param bool  $allowExtra
     * @param array $messages
     */
    public function __construct(
        $requiredKeys = null,
        $optionalKeys = null,
        $map = null,
        $deniedKeys = null,
        $allowExtra = null,
        $messages = null
    ) {
        $this->requiredKeys = $requiredKeys ?: [];
        $this->optionalKeys = $optionalKeys ?: [];
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
                'map'      => new ScalarValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'requiredKeys values must be strings',
                ],
            ]),
            'optionalKeys' => new ArrayValidator([
                'map'      => new ScalarValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'optionalKeys values must be strings',
                ],
            ]),
            'deniedKeys' => new ArrayValidator([
                'map'      => new ScalarValidator(['type' => 'string']),
                'messages' => [
                    'map' => 'deniedKeys values must be strings',
                ],
            ]),
            'allowExtra' => new ScalarValidator([
                'allowNull' => true,
                'type'      => 'boolean',
            ]),
            'messages' => new ArrayValidator([
                'map'          => new ScalarValidator(['type' => 'string']),
                'optionalKeys' => ['null', 'type', 'requiredKeys', 'deniedKeys', 'allowExtra'],
                'messages'     => [
                    'map' => 'messages values must be strings',
                ],
            ]),
        ]);
    }
}
