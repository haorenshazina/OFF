### **One File Framework（单文件框架）**

**运行需求：** PHP > 7.1

**项目描述：** 如其名，整个框架只有一个文件，项目并非常见的MVC框架，而是提供一些包装好的基础类使开发人员可以快速搭起项目框架或快速调试代码，目前只有自动加载功能和依赖注入功能，将不断完善。

---
**使用举例：**
依赖注入支持构造注入与方法注入

```php
include "OFF.php";

class Foo{

    protected $bar;

    public $foo = "Foo";

    public function __construct(Bar $bar){
        $this->bar = $bar;
    }

    public function callBarBaz(string $str = null)
    {
        $this->bar->callBaz($str);
    }
}

class Bar{

    protected $baz;

    public function __construct(Baz $baz){
        $this->baz = $baz;
    }

    public function fooBarBaz(Foo $foo, string $str)
    {
        echo $foo->foo."\n";
        $foo->callBarBaz($str);
    }

    public function callBaz($str)
    {
        $this->baz->bazCall($str);
    }
}

class Baz{

    public function bazCall($str)
    {
        echo "BAZ CALL {$str}\n";
    }
}
DependencyInject::getInstance(__NAMESPACE__.'\Foo')->callBarBaz();
DependencyInject::dependentCall(__NAMESPACE__.'\Bar', 'fooBarBaz', ['DependencyInject!']);
```
将输出：

```
BAZ CALL
Foo
BAZ CALL DependencyInject!
```
