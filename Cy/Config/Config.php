<?php
namespace Cy\Config;

use Cy\Mvc\Event\Event;

class Config extends Event
{
	const PHP = '.php';
	const INI =	'.ini';
	const XML = '.xml';

	private $_confBasePath    =   CONF;
	private $_conf            =   array();

	public function __construct($configBasePath)
	{
		parent::__construct();
		$this->_confBasePath  =   $configBasePath;
		$this->_conf          =   array();
	}

	private function iniConfig($configName)
	{
		$re = parse_ini_file($this->_confBasePath.$configName.'.ini', true);
		$this->_conf[$configName] = (array) $re;
		return 0;
	}

	private function xmlConfig($configName)
	{
		$config = simplexml_load_file($this->_confBasePath.$configName.'.xml');
		$this->_conf[$configName] = (array) $re;
		return 0;
	}

	private function phpConfig($configName)
	{
		require_once $this->_confBasePath.$configName.'.php';
        if(isset($config) && is_array($config))
    		$this->_conf[$configName] = $config;
        else
            $this->error('Can`t parse '.$configName.'.php.You should be all confiure in param $config and it must be array!',1014, true, 1);
		return 0;
	}

	public function getConfig($configName, $type = Config::PHP)
	{
		if ( isset($this->_conf[$configName]) )
			return $this->_conf[$configName];
        if ( file_exists($this->_confBasePath.$configName.$type) )
        switch($type)
		{
	    	case '.php':
	    		$this->phpConfig($configName);
	    	break;
	    	case '.ini':
	    		$this->iniConfig($configName);
	    	break;
	    	case '.xml':
	    		$this->xmlConfig($configName);
	    	break;
            default:
                $this->error('Invalid configure type '.$configName.'.'.$type.' been given', 1013, true, 1);
	    }
	    return $this->_conf[$configName];
	}
}
