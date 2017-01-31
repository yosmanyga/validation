# Property Validator

You can validate an object's property by using the ```PropertyValidator```:

    class User
    {
        public $name;
        public $age;
    }

    $user = new User();
    $user->age = 30;

    $validator = new PropertyValidator(array(
        new ScalarValidator(array(
            'type' => 'integer'
            'gt' => 0
        ))
    ));
    // Validates that the property 'age' is an integer and is greater than 0
    $errors = $validator->validate($user, 'age');

# Expression Property Validator

If you need a refined validator you can use the ```ExpressionPropertyValidator```:

    // Validates that the property is greater than 18 and lower than 35
    // "value" will be replaced by given property
    $validator = new ExpressionPropertyValidator(array(
        'expression' => 'value > 18 and value < 35'
    ));
    $validator->validate($user, 'age')

This validator uses the ExpressionLanguage Component of Symfony.

# Object Validator

You can validate many properties inside an object by using the ```ObjectValidator```:

    $user = new User();
    $user->name = 'John';
    $user->age = 30;

    // Validates that the property 'name' is a string
    // and the property 'age' is an integer and is greater than 0
    $validator = new ObjectValidator(array(
        'name' => new PropertyValidator(array(
            new ScalarValidator(array(
                'type' => 'string'
            ))
        )),
        'age' => new PropertyValidator(array(
            new ScalarValidator(array(
                'type' => 'integer'
                'gt' => 0
            ))
        ))
    ));
    $errors = $validator->validate($user);

If there are errors it will return its as an array of ```PropertyError```
objects.

## Short syntax

You can use the short syntax:

    $validator = new ObjectValidator(array(
        'name' => new ScalarValidator(array(
            'type' => 'string'
        )),
        'age' => new ScalarValidator(array(
            'type' => 'integer'
            'gt' => 0
        ))
    ));

Internally each validator will be converted to a ```PropertyValidator``` class.

# Validated Object Validator

You can define the validator inside the object itself, implementing the
```ValidatedInterface``` interface, and using the ```ValidatedObjectValidator```
class:

    class User implements ValidatedInterface
    {
        public $name;
        public $age;

        public function createValidator()
        {
            return new ObjectValidator(array(
                'name' => new ScalarValidator(array(
                    'type' => 'string'
                )),
                'age' => new ScalarValidator(array(
                    'type' => 'integer'
                    'gt' => 0
                ))
            ))
        }
    }

    $user = new User();
    $user->name = 'John';
    $user->age = 30;
    $validator = new ValidatedObjectValidator();
    $errors = $validator->validate($user);

# Nested Object

You can also validate nested objects:

    class Class1
    {
        public $property1;

        public $object2;
    }

    class Class2
    {
        public $property2;
    }

    $object2 = new Class2();
    $object2->property2 = array('foo');
    $object1 = new Class1();
    $object1->property1 = array('bar');
    $object1->object2 = $object2;

    $validator = new ObjectValidator(array(
        'property1' => new ScalarValidator(array('type' => 'string')),
        'object2' => new ObjectValidator(array(
            'property2' => new ScalarValidator(array('type' => 'integer')),
        ))
    ));
    /** @var \Yosmanyga\Validation\Validator\Error\PropertyError[] $errors */
    $errors = $validator->validate($object1);
    foreach ($errors as $error) {
        echo sprintf("%s: %s", $error->getPath(), $error->getText());
        echo "<br>";
    }

    // Output:
    // property1: Value must be of type "string"
    // object2.property2: Value must be of type "integer"

# Group Object Validator

If you need to have multiple validators for an object you can use a ```GroupObjectValidator```

    class User
    {
        public $name;
        public $age;
    }

    $user = new User();
    $user->name = 'John';
    $user->age = 30;

    $validator = new GroupObjectValidator(array(
        'Registration' => new ObjectValidator(array(
            'name' => new ScalarValidator(array(
                'type' => 'string'
            ))
        )),
        'Update' => new ObjectValidator(array(
            'name' => new ScalarValidator(array(
                'type' => 'string'
            )),
            'age' => new ScalarValidator(array(
                'type' => 'integer'
                'gt' => 0
            ))
        ))
    ));
    // Validates the object using the 'Update' group
    $errors = $validator->validate($user, 'Update');
