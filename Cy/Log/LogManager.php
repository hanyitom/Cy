<?php
namespace Cy\Log;

use Cy\Log\Log;

class LogManager extends Log
{
	protected function __construct()
	{
		parent::__construct(LOG);
	}

	public static function getInstance()
	{
		return new self();
	}

	public function getLogPath()
	{
		return $this->_logPath;
	}

	public function exception()
	{
        $this->_logFile = '-Db.log';
        return $this;
	}

	public function db()
	{
        $this->_logFile = '-Exception.log';
        return $this;
	}
}
