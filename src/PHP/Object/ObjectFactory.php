<?php
namespace ItForFree\rusphp\PHP\Object;

use ItForFree\rusphp\PHP\Object\ObjectClass\Constructor;

/**
 * Для порождения объектов
 */
class ObjectFactory {

    /**
     * Вернёт экземпляр класса -- или обычный объект или "одиночку" 
     * (по паттерну Singletone)
     * 
     * @param string $className   Имя класса
     * @param string $singletoneInstanceAccessStaticMethodName  необязательное имя статического метода для доступа к объекту-одиночке. По умолчанию 'get'
     * @return object
     */
    public static function getInstanceOrSingletone($className, 
        $singletoneInstanceAccessStaticMethodName = 'get')
    {
        $result = null;
        if (Constructor::isPublic($className)) {
            $result = new $className;
        } else {
            $result = call_user_func($className . '::' 
                . $singletoneInstanceAccessStaticMethodName); 
        }
       
        return $result;
    }
   
    /**
     * @param string $classname
     * @param array $config
     * @return null|object
     */
    public static function createObjectByConstruct(string $classname,
        array $config = [])
    {
        $resultObject = null;
        if (Constructor::isPublic($classname)) {
            $configObject = [];
            
            $reflection = new \ReflectionClass($classname);
            $constructor = $reflection->getConstructor();
            $parameters = $constructor->getParameters();
            
            /**
             * Собираем информацию о всех параметрах конструктора
             * и их типах данных
             */
            $reflectionParameters = [];
            foreach ($parameters as $parameter) {
                $reflectionParameters[$parameter->getName()] = $parameter;
            }

            foreach ($reflectionParameters as $argument => $parameter) {
                if (isset($config[$argument])) {
                    $dependency = $config[$argument];
                    unset($config[$argument]);
                } else {
                    foreach ($config as $key => $value) {
                        if (!isset($reflectionParameters[$key])) {
                            if (self::getType($value) == $parameter->getType()->getName()) {
                                $dependency = $value;
                                unset($config[$key]);
                                break;
                            }
                        }
                    }
                    
                    if (!isset($dependency)) {
                        try {
                            $dependency = $parameter->getDefaultValue();
                        } catch (\ReflectionException $e) {
                            $dependency = null;
                        }
                    }
                }
                
                $configObject[] = $dependency;
                unset($dependency);
            }

            $resultObject = $reflection->newInstanceArgs($configObject);
        }
        
        return $resultObject;
   }
   
   /**
    * 
    * @param any type $value
    * @return string
    */
   private static function getType($value = null)
   {
       if (is_object($value)) {
           $type = get_class($value);
       } else {
           $type = gettype($value);
       }
       
       switch ($type) {
           case 'integer':
               $type = 'int';
               break;
           
           case 'double':
               $type = 'float';
               break;
           
           case 'boolean':
               $type = 'bool';
               break;
       }
       
       return $type;
   }
}