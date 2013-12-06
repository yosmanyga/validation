# Value Validator

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

If there are errors it will return its as an array of ```Error``` objects.

## Available options:

- allowNull: Value can be null
- type: Value must be of this type
- eq: Value must be equal to this parameter
- neq: Value must not be equal to this parameter
- iq: Value must be identical to this parameter
- niq: Value must not be identical to this parameter
- gt: Value must be greater than this parameter
- ge: Value must be greater or equal than this parameter
- lt: Value must be lower than this parameter
- le: Value must be lower or equal than this parameter
- in: Value must be in this parameter array
- nin: Value must not be in this parameter array
- messages: Array of messages for each error

# Expression Value Validator

If you need a refined validator you can use the ```ExpressionValueValidator```:

    // Validates that a value is greater than 1 and lower than 10
    // "value" will be replaced by given value
    $validator = new ExpressionValueValidator(array(
        'expression' => 'value > 1 and value < 10'
    ));
    $errors = $validator->validate(11);

This validator uses the ExpressionLanguage Component of Symfony.