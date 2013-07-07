<?php
namespace Cy\Mvc\Event;

/**
 * 寄存实例
 * @author Toby
 */
class Events_Store
{	
	protected function __construct()
	{
		$this -> register_flag = false;
	}
	public static function getInstance()
	{
		return new self();
	}

	public function __set($namespace,$value)
	{
		if (!isset($this -> $namespace))
		{
			$this -> $namespace = $value;
			return true;
		}
		return false;
	}
	
	public function del($namespace)
	{
		unset($this -> $namespace);
	}
}