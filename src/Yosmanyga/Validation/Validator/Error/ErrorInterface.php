<?php

namespace Yosmanyga\Validation\Validator\Error;

/**
 * Interface used by error classes.
 */
interface ErrorInterface
{
    /**
     * @return string
     */
    public function getText();
}
