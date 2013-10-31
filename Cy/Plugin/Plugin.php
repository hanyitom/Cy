<?php
namespace Cy\Plugin;

use Cy\File\File;
use Cy\Plugin\PluginCachePool;

class Plugin
{
    private $_pluginCachePool   =   null;
    private $_pluginFile        =   null;

	private function __construct()
	{
        $this->_pluginCachePool = PluginCachePool::getInstance();
        $this->_pluginFile      = new File(PLUGIN);
	}

    public static function getInstance()
    {
        return new self();
    }

	public function getPluginObj($pluginName, $params)
	{
        if ($tmp = $this->_pluginCachePool->get($pluginName, $params))
            return $tmp;
		if( $tmp = $this->makePluginObj($pluginName, $params) )
			return $tmp;
	}

    private function makePluginObj($pluginName, $params)
    {
        $path = PLUGIN.$pluginName;
        if (in_array($pluginName, $this->_pluginFile->getDirs()))
            $path .= DIR_S.$pluginName.'.php';
        else if(in_array($pluginName.'.php', $this->_pluginFile->getFiles()))
            $path .= '.php';
        else
            return false;
        if (!file_exists($path))
            return false;
        require_once($path);
        return new $pluginName($params);
    }
}
