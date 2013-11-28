<?php

namespace Yosmanyga\Validation\Validator;

/**
 * Interface used by grouped validated classes.
 */
interface GroupValidatedInterface
{
    /**
     * @return \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    public function createValidator();
}
