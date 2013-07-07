<?php
namespace Cy\Config;
use Cy\Exception\Exception;
use Cy\Mvc\Event\Event;

class Config extends Event
{
	const PHP = '.php';
	const INI =	'.ini';
	const XML = '.xml';
	
	protected $config_base_path;
	protected $config;
	protected $type;
	
	public function __construct($config_base_path)
	{
		parent :: __construct();
		$this -> config_base_path = $config_base_path;
		$this -> config = array();
	}
	
	private function check($configName)
	{
		if ( file_exists($this -> config_base_path. $configName. $this -> type))
			return $this;
		$this -> error('No such file '. $configName. $this -> type. ' has been found in '. $this -> config_base_path, 1005);
		return $this;
	}
	
	private function iniConfig($configName)
	{
		$re = parse_ini_file($this -> config_base_path. $configName. $this -> type, true);
		$this -> config[$configName] = (array) $re;
		return 0;
	}
	
	private function xmlConfig($configName)
	{
		$config = simplexml_load_file($this -> config_base_path. $configName. $this -> type);
		$this -> config[$configName] = (array) $re;
		return 0;
	}
	
	private function phpConfig($configName)
	{
		require_once $this -> config_base_path. $configName. $this -> type;
		$this -> config = $config;
		return 0;
	}
	
	public function getConfig($configName, $type = Config :: PHP)
	{
		if ( isset($this -> config[$configName]) )
			return $this -> config[$configName];
		$this -> type = $type;
		$this -> check($configName)
			  -> read($configName);
		return $this -> config[$configName];
	}
	
	private function read($configName)
	{
		switch($this -> type)
		{
			case '.php':
				$this -> phpConfig($configName);
			break;
			case '.ini':
				$this -> iniConfig($configName);
			break;
			case '.xml':
				$this -> xmlConfig($configName);
			break;
		}
		return 0;
	}
}