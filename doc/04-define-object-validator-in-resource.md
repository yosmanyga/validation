# Validators in resource

You can use the library [yosmanyga/resource](https://github.com/yosmanyga/resource)
to store and load validators from a resource:

Actually you can define validators in yml, xml and annotation formats:

In this sample, "username" must be a string, and can't be null; "age" must be an
integer and between 18 and 35; and "roles" items must be string values.

validators.yml:

    Acme\DemoBundle\Model\User:
        properties:
            username:
                Value:
                    type: string
                    allowNull: false
            age:
                1:
                    validator: Value
                    options:
                        type: integer
                2:
                    validator: Expression
                    options:
                        expression: "value > 18 and value < 35"
                        message: "Value must be between 18 and 35"
            roles:
                Array:
                    map:
                        validator: Value
                        options:
                            type: string

validators.xml:

    <?xml version="1.0" encoding="UTF-8" ?>
    <validators>
        <class name="Acme\DemoBundle\Model\User">
            <property name="username">
                <validator name="Value">
                    <option name="type">string</option>
                    <option name="allowNull">false</option>
                </validator>
            </property>
            <property name="age">
                <validators>
                    <validator name="Value">
                        <option name="type">integer</option>
                    </validator>
                    <validator name="Expression">
                        <option name="expression">value &gt; 18 and value &lt; 35</option>
                        <option name="message">Value must be between 18 and 35</option>
                    </validator>
                </validators>
            </property>
            <property name="roles">
                <validator name="Array">
                    <option name="map">
                        <validator name="Value">
                            <option name="type">string</option>
                        </validator>
                    </option>
                </validator>
            </property>
        </class>
    </validators>

User.php:

    <?

    namespace Acme\DemoBundle\Model;

    class User
    {
        /**
         * @Validator\Value({
         *   type: "string",
         *   allowNull: false
         * })
         */
        public $username;

        /**
         * @Validator\Value({
         *   type: "integer",
         * })
         * @Validator\Expression({
         *   expression: "value > 18 and value < 35",
         *   message: "Value must be between 18 and 35"
         * })
         */
        public $age;

        /**
         * @Validator\Array({
         *   map: {
         *     validator: Value,
         *     options: {
         *       type: "string"
         *     }
         *   }
         * })
         */
        public $roles;
    }

Next create the loader and load validators from the resource:

index.php:

    use Yosmanyga\Validation\Resource\Loader\Loader;
    use Yosmanyga\Resource\Resource;

    $loader = new Loader();

    // Load validators

    $validators = $loader->load(
        new Resource(array('file' => 'validators.yml'))
    );
    /*
    $validators = $loader->load(
        new Resource(array('file' => 'validators.xml'))
    );
    */
    /*
    $validators = $loader->load(new Resource(
        array(
            'file' => 'User.php',
            'annotation' => '/^Validator\\\\/'
        ),
        'annotation'
    ));
    */

    $validator = $validators['Acme\DemoBundle\Model\User'];
    $user = new User();
    $user->username = 'joe';
    $user->age = 17;
    $user->roles = array('admin');

    print_r($validator->validate($user));


