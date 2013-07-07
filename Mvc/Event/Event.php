<?php
namespace Cy\Mvc\Event;

use Cy\Mvc\Event\Interface_Event;
use Cy\Mvc\Events_Manager;
use Cy\Mvc\Event\Event_Exception;

class Event implements Interface_Event
{	
	protected function __construct(){}
	
	final public function register($namespace,$eventObj)
	{
		$this -> getEvent_Register() -> register($namespace, $eventObj);
	}
	
	final public function getRegistered($namespace,$params = array())
	{
		return $this -> getEvent_Register() -> getRegistered($namespace, $params);
	}

	final public function attach($object, $func, $params = array())
	{
		$eventArr = array('obj' => $object,
						'func' => $func,
						'params' => $params
						);
		 $this -> getDi() -> attach($eventArr);
	}
	
	final public function error($message,$error_code, $lv = 'Notice', $trace = false, $previous = null)
	{
		$this -> attach(new Event_Exception($message, $error_code, $lv, $trace, $previous), 'showException');
	}
	
	final public function detach()
	{
		$this -> getDi() -> detach();
	}
	
	protected function getDi()
	{
		return Events_Manager :: getDi();
	}
	
	protected function getEvent_Register()
	{
		return Events_Manager :: getEvent_Register();
	}
}