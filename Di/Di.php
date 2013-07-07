<?php
namespace Cy\Di;

/**
 * 事件寄存器
 * @author Toby
 */
class Di
{
	private $process	=	array();
	private $error		=	array();

	/**
	 * 实例化寄存器
	 */
	private function __construct(){}
	
	/**
	 * 获取实例
	 */
	public static function getInstance()
	{
		return new self();
	}
	
	public function detach()
	{
		if ( !empty($this -> error) )
			call_user_func_array(array($this -> error['obj'],$this -> error['func']),$this -> error['params']);
		else
		{
			while( !empty($this -> process) )
			{
				$v = array_shift($this -> process);
				call_user_func_array(array($v['obj'],$v['func']),$v['params']);
			}
		}
	}
	
	public function attach($eventArr)
	{
		if ($eventArr['obj'] instanceof \Exception)
		{
			if ( empty($this -> error) )
				$this -> error = $eventArr; 
		}
		else
			$this -> process[] = $eventArr;
	}
	
	private function __clone(){}
}