<?php

namespace Yosmanyga\Validation\Validator\Error;

class PropertyError implements ErrorInterface
{
    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $text
     * @param string $path
     */
    public function __construct($text, $path = '')
    {
        $this->text = $text;
        $this->path = $path;
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

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param string $path
     */
    public function prependPath($path)
    {
        $this->path = sprintf("%s.%s", $path, $this->path);
    }
}
