# Array Validator

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

Available options:

- allowNull: Value can be null
- requiredKeys: Value must have these keys
- allowedKeys: Value can have these keys
- deniedKeys: Value can't have these keys
- allowExtra: Value can have extra keys
- messages: Array of messages for each error
