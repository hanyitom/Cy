<?php
namespace Cy\Plugin;

class PluginCachePool
{
    private $_pool  =   array();

    private function __construct(){}

    public static function getInstance()
    {
        return new self();
    }

    public function add($pluginName, $pluginObj, $params)
    {
        $this->_pool[$pluginName]['obj']    = serialize($pluginObj);
        $this->_pool[$pluginName]['params'] = $params;
    }

    public function get($pluginName, $params)
    {
        if ($params === null || $params === $this->_pool[$pluginName]['params'])
            return unserialize($this->_pool[$pluginName]['obj']);
        return false;
    }
}
?>
