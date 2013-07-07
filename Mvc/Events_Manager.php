<?php
namespace Cy\Mvc;

use Cy\Mvc\Model\Model;
use Cy\Log\Log_Manager;
use Cy\Config\Config;
use Cy\Di\Di;
use Cy\Loader\Loader;
use Cy\Mvc\Event\Event_Register;
use Cy\Mvc\Request;
use Cy\Mvc\Response;
use Cy\Mvc\Router;
use Cy\Mvc\View;
use Cy\Plugin\Plugin;

/**
 * 事件控制器
 * @author Toby
 *
 */
class Events_Manager
{
	protected static $Di;				//寄存器
	protected static $Event_Register;	//注册表
	private $isModules = false;
	private $Request;
	private $Router;
	private $Response;
	private $View;
	private $Config_Base_Info;
	
	/**
	 * 注册自动加载，并实例化寄存器、注册表。
	 * 引入并注册配置文件类
	 * @param String $config_path	配置文件夹路径
	 */
	private function __construct($user_config_path,$user_plugin_path = null)
	{
		define('Cy_ROOT', __DIR__. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR. '..'. DIRECTORY_SEPARATOR);
		spl_autoload_register(array('\Cy\Loader\Loader','autoLoad'));
		self :: $Di = Di :: getInstance();
		self :: $Event_Register = Event_Register :: getInstance();
		$config = new Config($user_config_path);
		$this -> Config_Base_Info = $config -> getConfig('BASE_INFO');
		if($user_plugin_path === null)
			$user_plugin_path = 'Cy\Plugin';
		$Plugin = new Plugin($user_plugin_path);
		self :: $Event_Register -> register('Cy\Plugin\Plugin',$Plugin);
		self :: $Event_Register -> register('Cy\Config\Config', $config);
		self :: $Event_Register -> register('Cy\Log\Log_Manager', Log_Manager :: getInstance());
		self :: $Event_Register -> register('Cy\Mvc\Model\Model', new Model());
//		if ( isset($this -> Config_Base_Info['Safe_Mode']) )
//			self :: $Di -> attach(array('obj' => $Plugin,
//										'func' => 'Safe_Mode',
//										'params' => array($this -> Config_Base_Info['Safe_Mode'])
//										)
//								);
//		if ( isset($this -> Config_Base_Info['Debug']) )
//			self :: $Di -> attach(array('obj' => $Plugin,
//										'func' => 'Debug',
//										'params' => array($this -> Config_Base_Info['Debug'])
//										)
//								  );
	}
	
	/**
	 * 实例化自身
	 * @param String $config_path	配置文件夹路径
	 */
	public static function getInstance($config_path)
	{
		return new self($config_path);
	}
	
	/**
	 * 获取请求事件
	 */
	public function getRequest()
	{
		$this -> Request = new Request();
		self :: $Event_Register -> register('Cy\Mvc\Requeset',$this -> Request);
		return $this;
	}
	
	/**
	 * 获取路由器事件
	 */
	public function getRouter()
	{
		$this -> Router = new Router($this -> Request, $this -> isModules);
		return $this;
	}
	
	/**
	 * 获取响应事件
	 */
	public function getResponse()
	{
		$this -> Response = new Response($this -> Router);
		return $this;
	}
	
	/**
	 * 获取视图事件
	 */
	public function getView()
	{
		$this -> View = new View($this -> Response, $this ->Config_Base_Info['template_path']);
		return $this;
	}
	
	/**
	 * 触发所有事件
	 */
	public function run()
	{
		self :: $Di -> detach();
	}
	
	/**
	 * 获取寄存器实例
	 */
	public static function getDi()
	{
		return self :: $Di;
	}
	
	/**
	 * 获取注册表实例
	 */
	public static function getEvent_Register()
	{
		return self :: $Event_Register;
	}
	
	/**
	 * 设置多模块
	 * @param Array $modules 模块信息.e.g.:array('bbs'=>'Modules\BBS')
	 */
	public function setModules($modules)
	{
		if ( $this -> isModules )
			$this -> isModules = true;
		if ( empty($this -> modules) )
			$this -> modules = $module;
		else
		{
			foreach( $modules as $k => $v)
				$this -> modules[$k] = $v;
		}
	}
	
	/**
	 * 返回是否设置了多模块
	 */
	public static function isModules()
	{
		return $this -> isModules;
	}
	
//	/**
//	 * 获取指定模块的命名空间
//	 * @param String $module 指定的模块
//	 */
//	public static function getModuleNamespace($module)
//	{
//		return $this -> modules[$module];
//	}
	
	private function __clone(){}
}