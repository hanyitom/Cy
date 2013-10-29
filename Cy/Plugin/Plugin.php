<?php
namespace Cy\Plugin;
use Cy\Mvc\Event\Event;
use Cy\Mvc\Events_Manager;

class Plugin extends Event
{
//	private $Plugin_root;
	private $File_obj;

	public function __construct()
	{
		parent::__construct();
//		$this -> Plugin_root = __DIR__;
		$this->File_obj = $this->getRegistered('Cy\File\File', PLUGIN);
	}

	private function Plugin_exists($Plugin)
	{
		if( $this->File_obj->dir_exists($Plugin) )
		{
			if (file_exists(PLUGIN. DIR_S.$Plugin.DIR_S.$Plugin.'.php') )
				return true;
			else
				$this -> error('No such Plugin class "'.$Plugin .'" has been found!', 1006);
		}
		else
			$this -> error('No such Plugin "'.$Plugin.'" has been found!', 1007);
		return false;
	}

	public function getPluginRoot()
	{
		return $this -> Plugin_root;
	}

	public function getPluginObj($Plugin, $params)
	{
		if( $this -> Plugin_exists($Plugin) )
			return $this -> getRegistered('Cy\Plugin\\'.$Plugin.'\\'.$Plugin, $params);
	}
}
