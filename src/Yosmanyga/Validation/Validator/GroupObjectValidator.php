<?php

namespace Yosmanyga\Validation\Validator;

class GroupObjectValidator implements GroupValidatorInterface
{
    /**
     * @var \Yosmanyga\Validation\Validator\ObjectValidator[]
     */
    private $validators;

    /**
     * @param \Yosmanyga\Validation\Validator\ObjectValidator[] $validators
     */
    public function __construct($validators)
    {
        $this->validators = $validators;
    }

    /**
     * @inheritdoc
     */
    public function validate($value, $groups = array())
    {
        $groups = $this->fixGroups($groups);

        $errors = array();
        foreach ($groups as $group) {
            if (!isset($this->validators[$group])) {
                throw new \InvalidArgumentException(sprintf("Group \"%s\" not found", $group));
            }

            $validator = $this->validators[$group];
            $validatorErrors = $validator->validate($value);
            if ($validatorErrors) {
                $errors = array_merge($errors, $validatorErrors);
            }
        }

        return $errors;
    }

    /**
     * @param  array $groups
     * @return array
     */
    private function fixGroups($groups)
    {
        if (!$groups) {
            $groups = array('Default');
        }

        return $groups;
    }
}
