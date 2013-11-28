<?php

namespace Yosmanyga\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;

class ArrayValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = array_replace_recursive(array(
            'allowNull' => true,
            'map' => null,
            'requiredKeys' => array(),
            'allowedKeys' => array(),
            'deniedKeys' => array(),
            'allowExtra' => true,
            'messages' => array(
                'null' => "Value can't be null",
                'type' => 'Value must be an array',
                'map' => 'Values are invalid',
                'requiredKeys' => 'These keys are required "%s"',
                'deniedKeys' => 'These keys are denied "%s"',
                'allowExtra' => 'Only these keys are allowed "%s"'
            )
        ), $options);
    }

    /**
     * @inheritdoc
     */
    public function validate($value)
    {
        $errors = array();

        $this->configureMessages();

        if (null === $value) {
            if (!$this->options['allowNull']) {
                $errors[] =  new Error($this->options['messages']['null']);
            }

            return $errors;
        }

        if (!is_array($value)) {
            $errors[] = new Error($this->options['messages']['type']);

            return $errors;
        }

        if (isset($this->options['map'])) {
            if (!is_callable($this->options['map'])) {
                throw new \InvalidArgumentException("Parameter \"map\" is not callable.");
            }
            $result = array_map($this->options['map'], $value);
            if ($result != array_filter($result)) {
                $errors[] =  new Error($this->options['messages']['map']);
            }

            return $errors;
        }

        if ($this->options['deniedKeys']) {
            $intersect = array_intersect($this->options['deniedKeys'], array_keys($value));
            if ($intersect) {
                $errors[] =  new Error($this->options['messages']['deniedKeys']);
            }
        }

        if ($this->options['requiredKeys']) {
            $diff = array_diff($this->options['requiredKeys'], array_keys($value));
            if ($diff) {
                $errors[] =  new Error($this->options['messages']['requiredKeys']);
            }
        }

        if (!$this->options['allowExtra']) {
            $diff = array_diff(array_keys($value), array_merge($this->options['deniedKeys'], $this->options['requiredKeys'], $this->options['allowedKeys']));
            if ($diff) {
                $errors[] =  new Error($this->options['messages']['allowExtra']);
            }

        }

        return $errors;
    }

    private function configureMessages()
    {
        if ($this->options['deniedKeys']) {
            $this->options['messages']['deniedKeys'] = sprintf($this->options['messages']['deniedKeys'], implode(", ", $this->options['deniedKeys']));
        }

        if ($this->options['requiredKeys']) {
            $this->options['messages']['requiredKeys'] = sprintf($this->options['messages']['requiredKeys'], implode(", ", $this->options['requiredKeys']));
        }

        if (false === $this->options['allowExtra']) {
            $this->options['messages']['allowExtra'] = sprintf($this->options['messages']['allowExtra'], implode(", ", array_merge($this->options['requiredKeys'], $this->options['allowedKeys'])));
        }
    }
}
