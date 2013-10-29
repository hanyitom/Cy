<?php
namespace Cy\Mvc\Event;

use Cy\Mvc\Event\InterfaceEvent;
use Cy\Mvc\EventsManager;
use Cy\Mvc\Event\EventException;

class Event implements InterfaceEvent
{
	protected function __construct(){}

	final public function register($namespace, $eventObj)
	{
		$this->getEventRegister()->register($namespace, $eventObj);
	}

	final public function getRegistered($namespace, $params = array())
	{
		return $this->getEventRegister()->getRegistered($namespace, $params);
	}

	final public function attach($object, $func, $params = array())
	{
		$eventArr = array('obj' => $object,
						'func'  => $func,
						'params'=> $params
						);
		$this->getDi()->attach($eventArr);
	}

	final public function error($message,$error_code, $trace = false, $lv = 0, $previous = null)
	{
		$this->attach(new EventException($message, $error_code, $trace, $lv, $previous), 'showException');
	}

	final public function detach()
	{
		$this->getDi()->detach();
	}

	protected function getDi()
	{
		return EventsManager::getDi();
	}

	protected function getEventRegister()
	{
		return EventsManager::getEventRegister();
	}
}
