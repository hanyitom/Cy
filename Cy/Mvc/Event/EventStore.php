<?php
namespace Cy\Mvc\Event;

use Cy\Mvc\Event\EventException;

/**
 * 寄存实例
 * @author Toby
 */
class EventStore
{
    private $_storage   =   array();

    private function __construct(){}

	public static function getInstance()
	{
		return new self();
	}

	public function set($namespace, $value, $isReplace)
	{
		if (!isset($this->_storage[$namespace]) || $isReplace)
		{
			$this->_storage[$namespace] = $value;
			return true;
        }
		return false;
	}

    public function get($namespace)
    {
        if (isset($this->_storage[$namespace]))
            return $this->_storage[$namespace];
        else
            throw new EventException("No such object $namespace been stored!", 1013, true);
    }

	public function del($namespace)
	{
        if (isset($this->_storage[$namespace]))
    		unset($this->_storage[$namespace]);
        else
            throw new EventException("No such object $namespace been stored!", 1013, true);
	}
}
