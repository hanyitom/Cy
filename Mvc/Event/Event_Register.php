<?php
namespace Cy\Mvc\Event;
use Cy\Mvc\Event\Interface_Event_Register;
use Cy\Mvc\Event\Event_Factory;
use Cy\Mvc\Event\Event_Exception;
use Cy\Mvc\Event\Events_Store;

/**
 * 事件实例寄存类(非驱动)
 * @author Toby
 */
class Event_Register extends Event_Factory implements Interface_Event_Register 
{
	/**
	 * 寄存对象
	 * @var Object Events
	 */
	public $Events_Store;
	
	/**
	 * 构造寄存对象
	 */
	private function __construct()
	{
		$this -> Events_Store = Events_Store :: getInstance();
	}
	
	/**
	 * 实例化对象
	 */
	public static function getInstance()
	{
		return new self();
	}
	
	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Interface_Event_Register::register()
	 */
	public function register($namespace,$eventObj)
	{
		$this -> Events_Store-> $namespace = $eventObj;
	}
	
	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Interface_Event_Register::getRegistered()
	 */
	public function getRegistered($namespace, $params = array())
	{
		if ( !$this -> isRegistered($namespace) )
		{
			$this -> register($namespace, $this -> get($namespace, $params));
		}
		return $this -> Events_Store -> $namespace;
	}
	
	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Interface_Event_Register::isRegistered()
	 */
	public function isRegistered($namespace)
	{
		return isset( $this-> Events_Store-> $namespace);
	}
	
	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Event_Factory::__call()
	 */
	public function __call($namespace, $params = array())
	{
		if ( !$this -> isRegistered($namespace) )
			$this -> register($namespace, parent :: get($namespace, $params));
		return $this -> Events_Store -> $namespace;
	}
	
	public function unRegister($namespace)
	{
		if ( $this -> isRegistered($namespace) )
			$this -> Events_Store -> del($namespace);
		else
			return false;
	}
}
