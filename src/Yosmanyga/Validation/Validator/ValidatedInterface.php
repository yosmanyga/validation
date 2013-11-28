<?php

namespace Yosmanyga\Validation\Validator;

/**
 * Interface used by validated classes.
 */
interface ValidatedInterface
{
    /**
     * @return \Yosmanyga\Validation\Validator\ObjectValidator
     */
    public function createValidator();
}
