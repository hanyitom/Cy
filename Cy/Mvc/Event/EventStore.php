<?php
namespace Cy\Mvc\Event;

use Cy\Mvc\Event\EventException;
use Cy\Loader\Loader;

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

	public function set($namespace, $obj, $params)
	{
		if (!isset($this->_storage[$namespace]))
        {
            $this->_storage[$namespace]['obj']      = $obj;
			$this->_storage[$namespace]['params']   = $params;
			return true;
        }
		return false;
	}

    public function get($namespace, $params)
    {
        if (isset($this->_storage[$namespace]) && $this->_storage[$namespace]['params'] == serialize($params))
            return $this->_storage[$namespace];
        else
            return $this->make($namespace, $params);
    }

	public function del($namespace)
	{
        if (isset($this->_storage[$namespace]))
    		unset($this->_storage[$namespace]);
        else
            throw new EventException("No such object $namespace been stored!", 1013, true);
	}

    public function make($namespace, $params = array())
	{
        try
        {
            Loader::loadClass($namespace);
            if (in_array('getInstance',get_class_methods($namespace)))
                $tmp = $namespace::getInstance($params);
            else
                $tmp = new $namespace($params);
            $this->set($namespace, $tmp, $params);
            return $tmp;
        }
        catch(LoaderException $e)
        {
            $this->attach($e, 'showException');
        }
	}
}
