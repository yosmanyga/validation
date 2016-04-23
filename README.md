Validation
==========

This library provides tools for validating simple values, arrays and objects.

You can validate a simple value by using the ```ValueValidator```:

    // Validates that the value is an integer and is greater than 10
    $validator = new ValueValidator(array(
        'type' => 'integer',
        'gt' => 10
    ));
    $errors = $validator->validate(9);
    foreach ($errors as $error) {
        echo $error;
        echo "\r\n";
    }

You can validate an array by using the ```ArrayValidator```:

    // Validates that a value is an array and keys must fit these requirements:
    // key1 and key2 are required
    // key3 is optional
    // key4 is denied
    $validator = new ArrayValidator(array(
        'requiredKeys' => array('key1', 'key2'),
        'allowedKeys' => array('key3'),
        'deniedKeys' => array('key4')
    ));
    $errors = $validator->validate(array('key1' => 'foo1'));

You can validate an object by using the ```ObjectValidator```:

    $user = new User();
    $user->name = 'John';
    $user->age = 30;

    // Validates that the property 'name' is a string
    // and the property 'age' is an integer and is greater than 0
    $validator = new ObjectValidator(array(
        'name' => new ValueValidator(array(
            'type' => 'string'
        )),
        'age' => new ValueValidator(array(
            'type' => 'integer'
            'gt' => 0
        ))
    ));
    $errors = $validator->validate($user);

# Documentation

Read the documentation for more information.
