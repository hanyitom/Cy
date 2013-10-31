<?php
namespace Cy\Mvc\Event;

use Cy\Mvc\Event\InterfaceEventRegister;
use Cy\Mvc\Event\EventFactory;
use Cy\Mvc\Event\EventStore;
use Cy\Mvc\EventsManager;

/**
 * 事件实例寄存类(非驱动)
 * @author Toby
 */
class EventRegister extends EventFactory implements InterfaceEventRegister
{
	/**
	 * 寄存对象
	 * @var Object Events
	 */
	private $_eventStore;

	/**
	 * 构造寄存对象
	 */
	private function __construct()
	{
		$this->_eventStore = EventStore::getInstance();
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
	public function register($namespace, $eventObj, $params = array())
	{
        $this->_eventStore->set($namespace, $eventObj, $params);
	}

	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Interface_Event_Register::getRegistered()
     */
	public function getRegistered($namespace, $params = array())
	{
		return $this->_eventStore->get($namespace, $params);
	}

	/**
	 * @see library/Cy/Mvc/Event/Cy\Mvc\Event.Event_Factory::__call()
	 */
	public function __call($namespace, $params = array())
	{
        try
        {
            return $this->getRegistered($namespace);
        }
        catch(EventException $e)
        {
            $obj = $this->make($namespace, $params);
            $this->register($namespace, $obj);
            return $obj;
        }
	}

	public function unRegister($namespace)
	{
        try
        {
            $this->_eventStore->del($namespace);
        }
        catch(EventException $e)
        {
            EventsManager::getDi()
                ->attach(array('obj'=>  $e,
                    'func'          =>  'showException',
                    'params'        =>  array()
                ));
        }
	}
}
