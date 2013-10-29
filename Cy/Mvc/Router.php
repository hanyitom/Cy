<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;
use Cy\Loader\Loader;
use Cy\Module\Module;

class Router extends Event
{
	private $_namespace =   '';
	private $_isModules =   false;
    private $_request   =   null;

	public function __construct($request, $isModules)
	{
		parent::__construct();
		$this->_request     =   $request;
        $this->_isModules   =   $isModules;
        $this->getDi()->detach();
		$this->attach($this, 'dispatch');
	}

	private function getNamespace()
	{
		$this->_namespace = 'controller\\'.$this->_request->getClass();
		if ( $this->_isModules )
			$this->_namespace = Module::getNamespace($this->_namespace);
	}

	public function dispatch()
	{
		$this -> getNamespace();
		if ( !class_exists($this->_namespace) )
			$this->error('No found such class has been requested!', 1001, true, 1);
	}

	public function endRouter()
	{
		$class  = $this->namespace;
		$action = $this->request->getAction();
		if ( !in_array($action, get_class_methods($class)) )
			$this->error('No such action has been found in class "'.$class.'"', 1010, true, 1);
		$c = new $class();
		return $c->$action();
	}
}
