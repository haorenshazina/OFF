<?php
/**
 * @category private
 * @author MaxwellZY.
 * @package One File Framework (OFF)
 * @since: 2019/4/19
 */

namespace OFFCore;

use ReflectionClass;

class Container
{
    public static function dependentCall(string $class, string $method, array $params = [])
    {
        $target = new ReflectionClass($class);

        return (new ReflectionClass($class))->newInstance(...$params);
    }

    //获取对象实例（内部循环处理多重依赖）
    public static function getInstance(string $class)
    {
        $target = new ReflectionClass($class);
        $constructor = $target->getConstructor();
        if(!$constructor){
            return $target->newInstance();
        }
        $parameters = $constructor->getParameters();
        $concrete = [];
        foreach($parameters as $param){
            //根据type hint获取依赖类名称
            $dependentClass = $param->getClass();
            if ($dependentClass) {
                $concrete[] = self::getInstance($dependentClass->name);
            }
        }
        return $target->newInstanceArgs($concrete);
    }
}


class a{
    public function __construct(b $b, t $t){
        $b->c($t);
    }
}
class b{
    public function c(t $t)
    {
        echo $t->p;
    }
}
class t{
    public $p = 'ok';
}
