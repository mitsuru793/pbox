<?php

require_once __DIR__ . '/../vendor/autoload.php';

class User
{
    use \Pbox\Box\HasStaticAttributes;
    use \Pbox\Box\AddsPropertyAccessToAttributes;

    private $name;
    private $_password;

    public function __construct(string $name, string $password)
    {
        $this->name = $this->setNameAttribute($name);
        $this->_password = $password;
    }

    /**
     * {@inheritdoc}
     */
    protected function isHiddenProperty(string $name): bool
    {
        return $name[0] === '_';
    }

    /**
     * {@inheritdoc}
     */
    protected function hasAttribute(string $name): bool
    {
        return !$this->isHiddenProperty($name) && property_exists($this, $name);
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
    $user->_password;
} catch (\Pbox\Exception\AccessStaticPropertyException $e) {
    $msg = $e->getMessage();
}
assert('Cannot access static property: _password' === $msg);

// call setter
$user->name = '  jane   ';
assert('Jane' === $user->name);
