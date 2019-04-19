### **One File Framework�����ļ���ܣ�**

**��������**PHP > 7.1
**��Ŀ������**���������������ֻ��һ���ļ�����Ŀ���ǳ�����MVC��ܣ������ṩһЩ��װ�õĻ�����ʹ������Ա���Կ��ٴ�����Ŀ��ܻ���ٵ��Դ��룬Ŀǰֻ���Զ����ع��ܺ�����ע�빦�ܣ����������ơ�

����ע��֧�ֹ���ע���뷽��ע��
**ʹ�þ�����**

```
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
�������

```
BAZ CALL
Foo
BAZ CALL DependencyInject!
```