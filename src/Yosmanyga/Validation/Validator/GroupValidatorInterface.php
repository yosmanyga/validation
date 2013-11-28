<?php

namespace Yosmanyga\Validation\Validator;

/**
 * Interface used by group validators.
 */
interface GroupValidatorInterface
{
    /**
     * @param  mixed                                         $value
     * @param  array                                         $groups
     * @throws \InvalidArgumentException                     If a group is not found
     * @return \Yosmanyga\Validation\Validator\Error\Error[]
     */
    public function validate($value, $groups = array());
}
