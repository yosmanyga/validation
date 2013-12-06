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
         *     validator: @Validator\Value,
         *     options: {
         *       type: "string"
         *     }
         *   }
         * })
         */
        public $roles;
    }

Next create the loader:

index.php:

    use Yosmanyga\Validation\Resource\Loader\Loader;
    use Yosmanyga\Resource\Reader\Iterator\DelegatorReader;
    use Yosmanyga\Resource\Reader\Iterator\XmlFileReader;
    use Yosmanyga\Resource\Reader\Iterator\YamlFileReader;
    use Yosmanyga\Resource\Reader\Iterator\SuddenAnnotationFileReader;
    use Yosmanyga\Resource\Normalizer\DelegatorNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\YamlFile\Normalizer as YamlFileNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ValueNormalizer as YamlFileValueNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ArrayNormalizer as YamlFileArrayNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\YamlFile\ExpressionNormalizer as YamlFileExpressionNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\XmlFile\Normalizer as XmlFileNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ValueNormalizer as XmlFileValueNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ArrayNormalizer as XmlFileArrayNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\XmlFile\ExpressionNormalizer as XmlFileExpressionNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\Normalizer as SuddenAnnotationFileNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ValueNormalizer as SuddenAnnotationFileValueNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ArrayNormalizer as SuddenAnnotationFileArrayNormalizer;
    use Yosmanyga\Validation\Resource\Normalizer\SuddenAnnotationFile\ExpressionNormalizer as SuddenAnnotationFileExpressionNormalizer;
    use Yosmanyga\Validation\Resource\Compiler\ObjectCompiler;
    use Yosmanyga\Resource\Compiler\DelegatorCompiler;
    use Yosmanyga\Validation\Resource\Compiler\ValueCompiler;
    use Yosmanyga\Validation\Resource\Compiler\ArrayCompiler;
    use Yosmanyga\Validation\Resource\Compiler\ExpressionCompiler;
    use Yosmanyga\Resource\Cacher\Cacher;
    use Yosmanyga\Resource\Cacher\Checker\FileVersionChecker;
    use Yosmanyga\Resource\Cacher\Storer\FileStorer;
    use Yosmanyga\Resource\Resource;

    $loader = new Loader(
        new DelegatorReader(array(
            new XmlFileReader(),
            new YamlFileReader(),
            new SuddenAnnotationFileReader()
        )),
        new DelegatorNormalizer(array(
            new YamlFileNormalizer(array(
                new YamlFileValueNormalizer(),
                new YamlFileArrayNormalizer(array(
                    new YamlFileValueNormalizer()
                )),
                new YamlFileExpressionNormalizer()
            )),
            new XmlFileNormalizer(array(
                new XmlFileValueNormalizer(),
                new XmlFileArrayNormalizer(array(
                    new XmlFileValueNormalizer()
                )),
                new XmlFileExpressionNormalizer()
            )),
            new SuddenAnnotationFileNormalizer(array(
                new SuddenAnnotationFileValueNormalizer(),
                new SuddenAnnotationFileArrayNormalizer(array(
                    new SuddenAnnotationFileValueNormalizer()
                )),
                new SuddenAnnotationFileExpressionNormalizer()
            ))
        )),
        new ObjectCompiler(
            new DelegatorCompiler(array(
                new ValueCompiler(),
                new ArrayCompiler(array(
                    new ValueCompiler()
                )),
                new ExpressionCompiler()
            ))
        ),
        new Cacher(
            new FileVersionChecker(
                new FileStorer(__DIR__ . "/cache", ".check")
            ),
            new FileStorer(__DIR__ . "/cache")
        )
    );

Then you can load validators from the resource:

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


