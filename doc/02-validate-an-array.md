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

## Applying a validator to each element

You can validate each element of the array by using the ```map``` option:

    // Validates that each element is an integer
    $validator = new ArrayValidator(array(
        'map' => function($e) {
            if (!is_integer($e)) {
                return 'Invalid element';
            }

            return null;
        }
    ));
    $errors = $validator->validate(array(50, 'foo'));

You can also use a validator as the ```map``` option:

    $validator = new ArrayValidator(array(
        'map' => new ValueValidator(array(
            'type' => 'integer'
        ))
    ));
    $errors = $validator->validate(array(50, 'foo'));

## Available options

- allowNull: Array can be null
- requiredKeys: Array must have these keys
- allowedKeys: Array can have these keys
- map: Applies the map to the elements of the array
- deniedKeys: Array can't have these keys
- allowExtra: Array can have extra keys
- messages: Array of messages for each error
