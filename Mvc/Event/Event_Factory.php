<?php
namespace Cy\Mvc\Event;
use Cy\Exception\Exception;
use Cy\Mvc\Events_Manager;

/**
 * 事件工厂类
 * @author Toby
 */
class Event_Factory
{
	/**
	 * 自动生产新实例
	 * @param String $funcName 指向到具体类的命名空间
	 * @param Array $params	参数
	 */
	public function __call($namespace, $params)
	{
		if (file_exists($namespace.'.php'))
		{
			if (class_exists($namespace))
			{
				require_once $namespace.'.php';
				return new $namespace($params);
			}
		}
	}
	
	/**
	 * 手动生产新实例
	 * @param String $funcName 指向到具体类的命名空间
	 * @param Array $params	参数
	 */
	public function get($namespace,$params)
	{
		try
		{
			if ( strpos($namespace,'model') === 0 )
			{
				$dir = $params['model_path'];
				$dir .= str_replace('model\\','',$namespace);
				$dir .= '.php';
				unset($params['model_path']);
			}
			else
			{
				$dir = Cy_ROOT;
				$dir .= str_replace('\\',DIRECTORY_SEPARATOR,$namespace);
				$dir .= '.php';
			}
			if ( file_exists( $dir ) )
			{
				require_once $dir;
				return new $namespace($params);
			}
			else
				throw new \Exception('No such class "'.$namespace.'" has been found',1001);
		}
		catch(\Exception $e)
		{
			$error = new Exception($e -> getMessage(),$e->getCode());
			Events_Manager :: getDi() -> attach(array('obj' => $error,
													'func' => 'showException',
													'params' => array() 
													)
											);
		}
	}

}