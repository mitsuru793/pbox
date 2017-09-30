# PBox

## こんな事に困っていませんか？

取り敢えずJSONでDBに保存。そして、デコードしたJSONをstdClassのまま使っていませんか？膨れ上がるJSON。スキーマが分からなくなってしまいます。そんな時はPboxを使い、ラッパークラスを作りスキーマを保証しましょう。

プロパティを外部に公開するのにアクセサは通したい。しかし、ただ返すだけの処理が多くて記述が冗長になっている。Pboxを使えば、自動でプロパティにアクセスした時にアクセサを通す事が可能です。返すだけならアクセサを定義する必要はありません。

## MagicalBox

MagicalBoxを継承するだけで、配列、JSON、stdClassに変換可能なクラスを作る事ができます。コンストラクタでundefinedなプロパティをJSONから取得しようとするとエラーになります。これによりスキーマの検証が事前に可能です。

公開するプロパティ名は`$attributes`に記述します。他のプロパティは内部的なメタ情報となり、外部に公開されません。

```php
<?php

use Pbox\MagicalBox;

/**
 * @property string $name
 * @property string $from
 */ 
class User extends MagicalBox
{
    /** @var array */
    protected $attributes = ['name', 'from'];
    
    // This value won't be output when convert format. ex: toJson()
    protected $meta;
    
    public function __construct(stdClass $json)
    {
        // You can write validation here.
        $this->name = $json->name; 
        
        // set default value
        $this->from = $json->from ?? ''; 
    }
    
    // auto call when access property
    public function name()
    {
        return "[$name]";
    }
}

$json = new stdClass;
$json->name = 'mitsuru793';
$json->from = 'japan';

$user = new User($json);

// ok
$user->name;

// bad...throws Exception. It's safety.
// You can confirm the json's property at User class.
$user->age;

// convert format
$user->toArray();
// ['name' => '[mitsuru793]', 'from' => 'japan]

$user->toJson(); // string
// '{"name":"[mitsuru793]","from":"japan"}'

$user->toObject(); // stdClass 
```

## MagicalHardBox

動的プロパティを使わない場合はMagicalHardBoxを使います。外部に公開するものはprotectedなプロパティで定義します。内部的なメタ情報はprivateで定義すれば良いですが、protectedにしたい場合は`$hidden`に記述しておけば、JSONなどに変換時に出力されなくなります。しかし、外部からアクセスは可能です。

メリット
+ ソースコードが分かりやすい。
+ プロパティにPhpDocを書きやすい。
+ 静的解析させやすい。

デメリット
+ 変換時に出力しないメタプロパティを`protected`にできない。
+ 上記を`protected`にしたい場合は、1つずつ`$hidden`に設定する必要がある。
+ `$hidden`を確認しないと、プロパティがメタ情報なのか分からない。
+ 子クラスにも継承させたいメタ情報は、外部からアクセスできてしまう。

```php
<?php

use Pbox\MagicalHardBox;

class User extends MagicalBox
{
    protected $hidden = ['meta2'];
    
    /** @var stdClass */
    protected $name;
    
    // meta property cannot be protected.
    private $meta1;
    
    // You can access this property from the outside.
    protected $meta2;
    
    public function __construct(stdClass $json)
    {
        $this->name = $json;
    }
    
    // auto call when access property
    public function name(string $name)
    {
        return "[$name]";
    }
}
```

## HardBox

自動でアクセサを定義させずに、カプセル化は自分で用意する場合はHardBoxを使います。JSONやarrayに変換する処理のみを継承します。

メリット

+ 既存のクラスに継承させても影響が少ない。
+ シンプルな仕組みで理解がし易い。
+ 静的解析をさせやすい。

デメリット

+ アクセサを全て記述する必要が面倒。
+ 子クラスにも継承させたいメタ情報は、外部からアクセスできてしまう。

```php
<?php

use Pbox\HardBox;

class User extends HardBox
{
    /** @var string handle name */
    protected $name;
    
    private $meta;
    
    public function __construct(stdClass $json)
    {
        $this->name = $json;
    }
    
    public function name(): string
    {
        return $this->name;
    }
}
```

## ValueObject

nickname, firstName, fullNameなど、同じ名前でも空白を含んだり、使える文字に制限があったりと仕様が違いますね。これはstringでは表現できません。そこで各stringのラッパークラスを作りましょう。ValueObjectを継承します。

このクラスが外部に公開するのは、ラップしたスカラ値だけです。何もヘルパーメソッドが無くともタイプヒントとしても扱えます。インスタンスの生成は`Name::of('mitsuru793)`と`new`を使わないので、通常のクラスと見分けがつきます。


```php
<?php

use Pbox\ValueObject;

class Name implements ValueObject
{
    /** @var string */
    protected $value;
    
    // Override to add typehint string. Parent construct has no typehint.
    protected function __construct(string $value)
    {
        parent::__construct($value);
    }
    
    public function __toString()
    {
        return $this->value;
    }
    
    public function hasSpace(): bool
    {
       return preg_match('/[ 　]/', $this->value);
    }
}
```

ValueObjectをBoxクラスのプロパティに入れると、JSONや配列に変換時にスカラ値に変換されます。

```php
<?php

use Pbox\MagicalHardBox;

class User extends MagicalHardBox
{
    /** @var Name handle name */
    protected $name;
    
    public function __construct(stdClass $json)
    {
        $this->name = Name::of($json->name);
    }
}

$json = new stdClass;
$json->name = 'mitsuru793';

$user = new User($json);

// When convert format, ValueObject will be convert to a value of $value property.;
$user->toArray();
// ['name' => 'mitsuru793']

$user->toJson();
// '{"name": "mitsuru793"}'

// name is not scala value.
$user->name->hasSpace();
(string)$user->name;
```

## フォーマットの変換時の設定

歴史的な理由からJSONのプロパティ名を変更できない時は、エイリアスで出力させるようにしたり、一部のプロパティを出力させないようにできます。

```php
<?php

use Pbox\HardBox;

class User extends HardBox
{
    protected $hidden = ['name'];
    protected $hiddenIfNull = ['age'];
    protected $alias = ['createdAt' => 'created'];
    
    protected $name;
    protected $age;
    protected $createdAt;
    
    public function __construct(stdClass $json)
    {
        // Abbreviation
    }
}

$json = new stdClass;
$json->name = 'mitsuru793';
$json->age = null;
$json->createdAt = null;

$user = new User($json);
$user->toArray();
// ['created' => null']
```

### $hidden

変換する時は、常に隠したいプロパティ名を列挙します。内部のメタ情報を定義していたり、レスポンスとしてのJSONのサイズを小さくする時に便利です。

### $hiddenIfNull

プロパティがnullの場合に、そのプロパティを出力しないようにします。スキーマを統一出来ない時に便利です。この動作をデフォルトにしたい場合は、`protected $hiddenAllIfNull = true;`を記述しましょう。

### $alias

JSONのプロパティがtypoだったり、分かりづらい短縮名だけど変更が出来ない。そういう時は、内部プロパティは明確にして、出力時に元に戻すようにしましょう。`$alias`はそのマッピングです。keyに変換元、valueに変換後のプロパティ名を記述します。

既にBoxクラスで定義されているプロパティ名として、出力したい時にも使えます。`['_allias' => 'alias']`
