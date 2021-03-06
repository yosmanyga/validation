<?php

namespace Yosmanyga\Validation\Validator;

use Yosmanyga\Validation\Validator\Error\Error;

class ScalarValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = array_replace_recursive([
            'allowNull' => false,
            'type'      => null,
            'eq'        => null,
            'neq'       => null,
            'iq'        => null,
            'niq'       => null,
            'gt'        => null,
            'ge'        => null,
            'lt'        => null,
            'le'        => null,
            'in'        => null,
            'nin'       => null,
            'messages'  => [
                'null' => "Value can't be null",
                'type' => 'Value must be of type "%s"',
                'eq'   => 'Value must be equal to "%s"',
                'neq'  => 'Value must not be equal to "%s"',
                'iq'   => 'Value must be identical to "%s"',
                'niq'  => 'Value must not be identical to "%s"',
                'gt'   => 'Value must be greater than "%s"',
                'ge'   => 'Value must be greater or equal to "%s"',
                'lt'   => 'Value must be lower than "%s"',
                'le'   => 'Value must be lower or equal to "%s"',
                'in'   => 'Value must be one of these values "%s"',
                'nin'  => 'Value must not be one of these values "%s"',
            ],
        ], $options);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $errors = [];

        $this->configureMessages();

        if ($value === null) {
            if ($this->options['allowNull'] === false) {
                return [new Error($this->options['messages']['null'])];
            }

            return [];
        }

        if ($this->options['type'] !== null && $this->options['type'] != gettype($value)) {
            $errors[] = new Error($this->options['messages']['type']);
        }

        if ($this->options['eq'] !== null && $value != $this->options['eq']) {
            $errors[] = new Error($this->options['messages']['eq']);
        }

        if ($this->options['neq'] !== null && $value == $this->options['neq']) {
            $errors[] = new Error($this->options['messages']['neq']);
        }

        if ($this->options['iq'] !== null && $value != $this->options['iq']) {
            $errors[] = new Error($this->options['messages']['iq']);
        }

        if ($this->options['niq'] !== null && $value == $this->options['niq']) {
            $errors[] = new Error($this->options['messages']['niq']);
        }

        if ($this->options['gt'] !== null && $value <= $this->options['gt']) {
            $errors[] = new Error($this->options['messages']['gt']);
        }

        if ($this->options['ge'] !== null && $value < $this->options['ge']) {
            $errors[] = new Error($this->options['messages']['ge']);
        }

        if ($this->options['lt'] !== null && $value >= $this->options['lt']) {
            $errors[] = new Error($this->options['messages']['lt']);
        }

        if ($this->options['le'] !== null && $value > $this->options['le']) {
            $errors[] = new Error($this->options['messages']['le']);
        }

        if ($this->options['in'] !== null && !in_array($value, $this->options['in'])) {
            $errors[] = new Error($this->options['messages']['in']);
        }

        if ($this->options['nin'] !== null && in_array($value, $this->options['nin'])) {
            $errors[] = new Error($this->options['messages']['nin']);
        }

        return $errors;
    }

    private function configureMessages()
    {
        if ($this->options['type'] !== null) {
            $this->options['messages']['type'] = sprintf($this->options['messages']['type'], $this->options['type']);
        }
        if ($this->options['eq'] !== null) {
            $this->options['messages']['eq'] = sprintf($this->options['messages']['eq'], print_r($this->options['eq'], true));
        }
        if ($this->options['neq'] !== null) {
            $this->options['messages']['neq'] = sprintf($this->options['messages']['neq'], print_r($this->options['neq'], true));
        }
        if ($this->options['iq'] !== null) {
            $this->options['messages']['iq'] = sprintf($this->options['messages']['iq'], print_r($this->options['iq'], true));
        }
        if ($this->options['niq'] !== null) {
            $this->options['messages']['niq'] = sprintf($this->options['messages']['niq'], print_r($this->options['niq'], true));
        }
        if ($this->options['gt'] !== null) {
            $this->options['messages']['gt'] = sprintf($this->options['messages']['gt'], print_r($this->options['gt'], true));
        }
        if ($this->options['ge'] !== null) {
            $this->options['messages']['ge'] = sprintf($this->options['messages']['ge'], print_r($this->options['ge'], true));
        }
        if ($this->options['lt'] !== null) {
            $this->options['messages']['lt'] = sprintf($this->options['messages']['lt'], print_r($this->options['lt'], true));
        }
        if ($this->options['le'] !== null) {
            $this->options['messages']['le'] = sprintf($this->options['messages']['le'], print_r($this->options['le'], true));
        }
        if ($this->options['in'] !== null) {
            $this->options['messages']['in'] = sprintf($this->options['messages']['in'], implode(', ', $this->options['in']));
        }
        if ($this->options['nin'] !== null) {
            $this->options['messages']['nin'] = sprintf($this->options['messages']['nin'], implode(', ', $this->options['nin']));
        }
    }
}
