<?php
namespace Cy\Mvc\Event;
use Cy\Exception\Exception;
use Cy\Loader\Loader;
use Cy\Mvc\EventsManager;

/**
 * 事件工厂类
 * @author Toby
 */
class EventFactory
{
	/**
	 * 自动生产新实例
	 * @param String $funcName 指向到具体类的命名空间
	 * @param Array $params	参数
	 */
	public function __call($namespace, $params)
	{
        return $this->make($namespacem, $params)
	}

	/**
	 * 手动生产新实例
	 * @param String $funcName 指向到具体类的命名空间
	 * @param Array $params	参数
	 */
	public function make($namespace, $params)
	{
        try
        {
            Loader::loadClass($namespace);
            return new $namespace($params);
        }
        catch(LoaderException $e)
        {
            EventsManager::getDi()
                ->attach(array('obj'=>$e,
                    'func'          =>'showException',
                    'params'        =>array());
        }
	}
}
