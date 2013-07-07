<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;
use Cy\Loader\Loader;

class Router extends Event
{
	private $namespace = null;
	private $module = null;
//	private $isModules = null;
	
	public function __construct($request,$isModules)
	{
		parent :: __construct();
		$this -> request = $request;
		$this -> getDi() -> detach();
		$this -> isModules = $isModules;
		$this -> attach($this , 'dispatch');
	}
	
	private function check()
	{
		return class_exists($this -> namespace);
	}
	
	private function getNamespace()
	{
		$this -> namespace = 'controller\\'. $this -> request -> getClass();
//		if ( $this -> isModules )
//		{
//			待修改
//			$this -> namespace = Events_Manager :: getModuleNamespace($this -> getModule()) . $this -> namespace;
//		}
	}
	
	public function dispatch()
	{
		$this -> getNamespace();
		if ( !$this -> check($this -> namespace) )
			$this -> error('No found such class has been requested!', 1001);
	}
	
	public function endRouter()
	{
		$class = $this -> namespace;
		$action = $this -> request -> getAction();
		if ( !in_array($action,get_class_methods($class)) )
			$this -> error('No such action has been found in class "'.$class.'"', 1010, 'fatal');
		$c = new $class();
		return $c -> $action();
	}
}