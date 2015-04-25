<?php
namespace Cy\Di;

/**
 * 事件寄存器
 * @author Toby
 */
class Di
{
	private $process	=	array();
	private $exception	=	array();

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
		if ( !empty($this->exception) )
            call_user_func_array(array($this->exception['obj'], $this->exception['func']),
                $this->exception['params']);
		else
		{
			while( !empty($this->process) )
			{
				$v = array_shift($this->process);
				call_user_func_array(array($v['obj'], $v['func']), $v['params']);
			}
		}
	}

	public function attach($eventArr)
	{
		if ($eventArr['obj'] instanceof \Exception)
		{
			if ( empty($this->exception) )
                $this->exception = $eventArr;
            else
                $this->detach();
		}
		else
			$this->process[] = $eventArr;
	}

	private function __clone(){}
}

interface wrapper{
	protected $_list;
	protected $_callback_list;
	public function attach($args);
	public function detach();
	public function get($name);
	public function refresh($name);
}
