<?php
namespace Cy\Log;
use Cy\Log\Log;

abstract class AbstractLog
{
	protected $log_path;
	/**
	 * 如果$log_file = Log_Manager :: FILE,为string，可直接使用
	 * 如果$log_file = Log_Manager :: DB，为数组，当记录DB时array('date'=>date,'sql'=>sql,'type'=>type)
	 * 记录ERROR时array('date'=>date,'error_info'=>error_info,'lv'=>lv)
	 * type为create、alter、insert、update、delete、select
	 * @var mixed
	 */
	protected $log_message;
	protected $log_type;
	private $method = array('Add','Del','Get','Message');
	
	protected function __construct($log_path, $log_type)
	{
		$this -> log_path = $log_path;
		$this -> log_type = $log_type;
	}
	
	public function __call($method, $params)
	{
		$method = ucfirst(strtolower($method));
		if ( !in_array( $method, $this -> method ) )
			return false;
		switch( $this -> log_type )
		{
			case 1:
				$method = 'db'.$method;
			break;
			case 2:
				$method = 'file'.$method;
			break;
		}
		return $this -> $method($params);
	}
	
	abstract protected function dbMessage($params);
	abstract protected function fileMessage($params);
	abstract protected function fileAdd();
	abstract protected function dbAdd();
	abstract protected function dbGet($params);
	abstract protected function fileGet($params);
}
?>
