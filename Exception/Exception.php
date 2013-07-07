<?php
namespace Cy\Exception;
use Cy\Mvc\Events_Manager;

class Exception extends \Exception
{
	private $string;										//
	private $lv;											//错误分级，目前支持Fatal、Warning和Notice
	private $isTrace;										//是否追踪
	
	public function __construct ($message, $code, $lv = 'Fatal', $isTrace = false, $previous = null)
	{
		parent :: __construct($message, $code, $previous);
		$this -> lv = ucfirst(strtolower($lv));
		$this -> isTrace = $isTrace;
		$this -> string = $this -> lv. ': '. $this -> getMessage().' ErrorCode: '. $this -> getCode(). ' in '. $this -> getFile(). ' on '. $this -> getLine();
		if ( $isTrace )
			$this -> string .= ' \n\r '. $this -> getTraceAsString(); 
	}

	public function __toString () 
	{
		return $this -> string;
	}
	
	public function showException()
	{
		$this -> ExceptionLog();
		//待添加ERROR页
		if ( $this -> lv == 'Fatal' )	//致命错误，停止继续执行
		{
//			header('Location:错误页');
//			die();
			echo memory_get_peak_usage(true).'bytes<br />';
			echo memory_get_usage(true).'bytes<br />';
			die( $this -> string );
		}
		return true;
	}
	
	public function ExceptionLog()
	{
		Events_Manager :: getEvent_Register() -> getRegistered('Cy\Log\Log_Manager')
											  -> Exception()
											  -> Message( $this -> string )
											  -> Add();
	}
}
?>