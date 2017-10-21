<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Class User
 * @property string $name
 */
class User
{
    use \Pbox\Box\HasDynamicAttributes;
    use \Pbox\Box\AddsPropertyAccessToAttributes;

    // can access to this has elements from outside
    private $attributes = [
        'name' => '',
    ];

    // cannot access from outside
    private $password;

    public function __construct(string $name, string $password)
    {
        // name is in $attributes
        $this->name = $this->setNameAttribute($name);

        // password is a property
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    protected function hasAttribute(string $name): bool
    {
        return isset($this->attributes[$name]);
    }

    protected function setNameAttribute(string $value): string
    {
        return trim($value);
    }

    protected function getNameAttribute(string $value): string
    {
        return ucfirst($value);
    }
}

$user = new User('  mike ', 'hogehoge');

// call getter
assert('Mike' === $user->name);

// cannot access hidden
$msg = null;
try {
    $user->password;
} catch (\Pbox\Exception\AccessStaticPropertyException $e) {
    $msg = $e->getMessage();
}
assert('Cannot access static property: password' === $msg);

// call setter
$user->name = '  jane   ';
assert('Jane' === $user->name);
