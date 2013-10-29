<?php
namespace Cy\Mvc;

use Cy\Mvc\Model\Model;
use Cy\Log\LogManager;
use Cy\Config\Config;
use Cy\Exception\Exception;
use Cy\Loader\Loader;
use Cy\Di\Di;
use Cy\Mvc\Event\EventRegister;
use Cy\Mvc\Request;
use Cy\Mvc\Response;
use Cy\Mvc\Router;
use Cy\Mvc\Render;
use Cy\Plugin\Plugin;

/**
 * 事件控制器
 * @author Toby
 *
 */
class EventsManager
{
	protected static $_di               =   null;
	protected static $_eventRegister    =   null;
	protected static $_isModules        =   false;
    protected static $_modules          =   array();
	protected static $_baseConf         =   null;
    protected static $_exception        =   null;

	/**
	 * 注册自动加载，并实例化寄存器、注册表。
	 * 引入并注册配置文件类
	 * @param String $config_path	配置文件夹路径
	 */
	private function __construct()
	{
		spl_autoload_register(array('\Cy\Loader\Loader', 'autoLoad'));
        $config = new Config(CONF);
        $plugin = new Plugin(PLUGIN);
        self::$_eventRegister   =   EventRegister::getInstance();
        self::$_di              =   Di::getInstance();
        self::$_baseConf        =   $config->getConfig('BASE_INFO');
		self::$_eventRegister   ->register ('Cy\Plugin\Plugin',     $plugin);
		self::$_eventRegister   ->register ('Cy\Config\Config',     $config);
		self::$_eventRegister   ->register ('Cy\Log\Log_Manager',   LogManager::getInstance());
        self::$_eventRegister   ->register ('Cy\Mvc\Model\Model',   new Model());
//等待DEBUG模块
//		if ( DEBUG )
//			self :: $Di -> attach(array('obj' => $Plugin,
//										'func' => 'Debug',
//										'params' => array($this -> Config_Base_Info['Debug'])
//										)
//								  );
	}

	public static function run()
    {
        if (self::$_exception !== null)
            self::$_exception->showException();
        new self();
        new View(new Response(new Router(new Request(), self::$_isModules)));
        self::$_di->detach();
	}

   	/**
	 * 获取注册表实例
	 */
	public static function getEventRegister()
	{
		return self::$_eventRegister;
	}

	/**
	 * 设置多模块
	 * @param Array $modules 模块信息.e.g.:array(module_name=>module_path)
	 */
	public static function setModules($modules)
	{
        if (!is_array($modules))
        {
            self::$_exception = new Exception('Valid param $modules been given.$modules must be array!', 1012, false);
            return false;
        }
        self::$_isModules = true;
		foreach( $modules as $k => $v)
			self::$_modules[$k] = $v;
        return true;
	}

    public static function getModules()
    {
        return self::$_modules;
    }

    public static function getBaseConf()
    {
        return self::$_baseConf;
    }

	private function __clone(){}
}
