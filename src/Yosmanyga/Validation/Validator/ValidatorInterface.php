<?php

namespace Yosmanyga\Validation\Validator;

/**
 * Interface used by validators.
 */
interface ValidatorInterface
{
    /**
     * @param  mixed                                         $value
     * @return \Yosmanyga\Validation\Validator\Error\Error[]
     */
    public function validate($value);
}
