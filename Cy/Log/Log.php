<?php
namespace Cy\Log;

abstract class Log
{
	protected $_logPath;
	protected $_logMessage;
    protected $_logFile;
	protected $_logType;

	protected function __construct($logPath)
	{
		$this->_logPath = $logPath;
	}

    public function message($msg)
    {
        $this->_logMessage = date('[Y-m-d H:i:s]  ', $_SERVER['REQUEST_TIME']).$msg." \r\n";
		return $this;
    }

    public function add()
    {
        $path = $this->_logPath.date('[Y-m-d]',$_SERVER['REQUEST_TIME']).$this->_logFile;
        file_put_contents($path, $this->_logMessage, FILE_APPEND|LOCK_EX);
		return true;
    }

    public function get($date)
    {
        $path = $this->_logPath.date('T-m-d', $date).$this->_logFile;
		if ( file_exists( $path ) )
			$re = file_get_contents($path);
		else
			return null;
		return $re;
    }
}
?>
