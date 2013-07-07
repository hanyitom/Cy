<?php
namespace Cy\Log;
use Cy\Mvc\Event\Event;
use Cy\Log\Error_Log;
use Cy\Log\Db_Log;

class Log_Manager extends Event
{
	/**
	 * 不对执行过的SQL语句进行记录
	 * @var INTEGER
	 */
	const NONE = 0;
	/**
	 * 将执行过的SQL语句记录到数据库
	 * @var INTEGER
	 */
	const DATABASE = 1;
	/**
	 * 将执行过的SQL语句记录到文件
	 * @var INTEGER
	 */
	const FILE = 2;
	protected $log_path		=	null;
	protected $Error_Log	=	null;
	protected $Db_Log		=	null;
	protected $Log_type		=	null;
	
	protected function __construct()
	{
		$this -> Log_type = isset($Base_Config_Info['log_type']) ? $Base_Config_Info['log_type'] : self :: FILE;
		if ($this -> Log_type == Log_Manager :: NONE)
			return false;
		parent :: __construct();
		$Base_Config_Info = $this -> getEvent_Register() -> getRegistered('Cy\Config\Config') -> getConfig('BASE_INFO');
		$this -> log_path = isset($Base_Config_Info['log_path']) ? $Base_Config_Info['log_path'] : Cy_ROOT. '..'. DIRECTORY_SEPARATOR.'log';
		$this -> checkPath();
		$this -> Db_Log = Db_Log :: getInstance($this -> log_path, $this -> Log_type);
		$this -> Error_Log = Error_Log :: getInstance($this -> log_path, $this -> Log_type);
	}
	
	public static function getInstance()
	{
		return new self();
	}
	
	public function get_log_path()
	{
		return $this -> log_path;
	}
		
	public function exception()
	{
		if ( $this -> Error_Log != null )
			return $this -> Error_Log;
	}
	
	public function db()
	{
		if ( $this -> Db_Log != null )
			return $this -> Db_Log;
	}
	
	private function checkPath()
	{
		if (is_dir($this -> log_path))
			return true;
		else
			mkdir($this -> log_path,0744, true);
	}
	
	public function reset($log_path,$type = null)
	{
		if ($type !== null)
			$this -> Log_type = $type;
		$this -> log_path = $log_path;
		$this -> Error_Log = Error_Log :: getInstance($this -> log_path,$this -> Log_type);
		$this -> Db_Log = Db_Log :: getInstance($this -> $log_path, $this -> Log_type);
	}
}