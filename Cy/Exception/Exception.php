<?php
namespace Cy\Exception;

use Cy\Mvc\EventsManager;

class Exception extends \Exception
{
	private $_message   =   '';
    private $_lvs       =   array('NOTICE', 'FATAL', 'WARNING');
    private $_isTrace   =   false;
    private $_lv        =   'FATAL';

	public function __construct ($message, $code, $isTrace = false, $lv = 1, $previous = null)
	{
		parent::__construct($message, $code, $previous);
		$this->_isTrace  = $isTrace;
		$this->_lv       = $this->_lvs[$lv];
        $this->_message  = $this->_lv.': '.$this->getMessage().' ErrorCode: '.
            $this->getCode().' in '.$this->getFile().' on '.$this->getLine();
		if ( $isTrace )
			$this->_message .= ' \n\r '.$this->getTraceAsString();
	}

	public function __toString ()
	{
		return $this->_message;
	}

	public function showException()
	{
		$this->exceptionLog();
		//待添加ERROR页
		if ( $this->_lv == 'Fatal' )	//致命错误，停止继续执行
		{
//			header('Location:错误页');
//			die();
			echo memory_get_peak_usage(true).'bytes<br />';
			echo memory_get_usage(true).'bytes<br />';
			die( $this->_message );
		}
		return true;
	}

	public function exceptionLog()
	{
        EventsManager::getEventRegister()->getRegistered('Cy\Log\LogManager')
										 ->exception()
										 ->message( $this->_message )
										 ->add();
	}
}
?>
