<?php
/**
 * @category private
 * @author MaxwellZY
 * @package One File Framework (OFF)
 * @since: 2019/4/19
 */

namespace OFFCore;

use Exception;
use ReflectionClass;


class OFFException extends Exception {};

//依赖注入
class DependencyInject
{
    //缓存类反射的结果，降低每次反射的代价
    public static $classMap = [];

    public static function dependentCall(string $class, string $method, array $params = [])
    {
        $instance = self::getInstance($class);
        if(!self::$classMap[$class]->hasMethod($method)){
            throw new OFFException("调用的方法不存在");
        }
        $targetMethod = self::$classMap[$class]->getMethod($method);
        $parameters = $targetMethod->getParameters();
        $concrete = [];
        foreach($parameters as $param){
            //根据type hint获取依赖类名称
            $dependentClass = $param->getClass();
            if ($dependentClass) {
                $concrete[] = self::getInstance($dependentClass->name);
            }
        }

        return $targetMethod->invokeArgs($targetMethod->isStatic()? null : $instance, array_merge($concrete, $params));
    }

    //获取对象实例（内部循环处理多重依赖）
    public static function getInstance(string $class)
    {
        if(isset(self::$classMap[$class])){
            $target = self::$classMap[$class];
        }
        else{
            $target = new ReflectionClass($class);
            self::$classMap[$class] = $target;
        }
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

//基于namespace的自动加载器
class Autoloader
{
    private const TOP_DIR = __DIR__;

    public static function autoload($class){
        $file = self::find($class);

        if (file_exists($file) && is_file($file)) {
            include_once ($file);
        }
    }

    private static function find($className)
    {
        return strtr(self::TOP_DIR.$className, '\\', DIRECTORY_SEPARATOR).'.php'; // 类名转为路径
    }
}

spl_autoload_register(__NAMESPACE__.'\Autoloader::autoload');