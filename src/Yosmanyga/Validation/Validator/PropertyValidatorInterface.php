<?php

namespace Yosmanyga\Validation\Validator;

/**
 * Interface used by property validators.
 */
interface PropertyValidatorInterface
{
    /**
     * @param  object                                        $object
     * @param  string                                        $property
     * @return \Yosmanyga\Validation\Validator\Error\Error[]
     */
    public function validate($object, $property);
}
