<?php

namespace Yosmanyga\Validation\Validator\Error;

class Error implements ErrorInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return (string) $this->text;
    }

    /**
     * @inheritdoc
     */
    public function getText()
    {
        return $this->text;
    }
}
