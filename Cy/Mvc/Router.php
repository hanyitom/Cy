<?php
namespace Cy\Mvc;

use Cy\Mvc\EventsManager;
use Cy\Loader\Loader;
use Cy\Module\Modules;

class Router
{
	private $_namespace =   '';
	private $_isModules =   false;
    private $_request   =   null;

	private function __construct($request, $isModules)
	{
		$this->_request     =   $request;
        $this->_isModules   =   $isModules;
        EventsManager::getDi()->detach();
        EventsManager::getDi()
            ->attach(array('obj'=>  $this,
                        'func'  =>  'dispatch',
                        'params'=>  array()));
	}

    public static function getInstance($request, $isModules)
    {
        return new self($request, $isModules);
    }

	private function getNamespace()
	{
		$this->_namespace = 'controller\\'.$this->_request->getClass();
		if ( $this->_isModules )
			$this->_namespace = Modules::getNamespace($this->_namespace);
	}

	public function dispatch()
	{
		$this -> getNamespace();
		if ( !class_exists($this->_namespace) )
			$this->error('No found such class has been requested!', 1001, true, 1);
	}

	public function endRouter()
	{
		$class  = $this->_namespace;
		$action = $this->_request->getAction();
		if ( !in_array($action, get_class_methods($class)) )
			$this->error('No such action has been found in class "'.$class.'"', 1010, true, 1);
		$c = new $class();
		return $c->$action();
	}
}
