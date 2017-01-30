<?php

namespace Yosmanyga\Validation\Validator;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Yosmanyga\Validation\Validator\Error\Error;

class ExpressionValueValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * @param string                                                   $expression
     * @param array                                                    $options
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage
     */
    public function __construct($expression = '', $options = [], ExpressionLanguage $expressionLanguage = null)
    {
        $this->expression = $expression;
        $this->options = array_replace([
            'message' => 'This value is not valid',
        ], $options);
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value)
    {
        $errors = [];

        $this->addVariable('value', $value);

        // Sometime ExpressionValueValidator is constructed without the
        // ExpressionLanguage object, so it can be cached. ExpressionLanguage
        // can't be cached because of closures.
        $this->expressionLanguage = $this->expressionLanguage ?: new ExpressionLanguage();
        if (!$this->expressionLanguage->evaluate($this->expression, $this->variables)) {
            $errors[] = new Error($this->options['message']);
        }

        return $errors;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }
}
