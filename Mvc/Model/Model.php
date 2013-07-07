<?php
namespace Cy\Mvc\Model;
use Cy\Mvc\Event\Event;

class Model extends Event
{
	protected $model_path;
	protected $db;
	
	public function __construct()
	{
		parent :: __construct();
		$Config_Base_Info = $this -> getEvent_Register()
								  -> getRegistered('Cy\Config\Config')
								  -> getConfig('BASE_INFO');
		if ( isset($Config_Base_Info['model_path']) )
		{
			$l = strlen($Config_Base_Info['model_path']);
			if ( $Config_Base_Info['model_path'][$l-1] != DIRECTORY_SEPARATOR )
				$Config_Base_Info['model_path'] = $Config_Base_Info['model_path']. DIRECTORY_SEPARATOR;
			$this -> model_path = $Config_Base_Info['model_path'];
		}
		else
			$this -> model_path = Cy_ROOT. '..'. DIRECTORY_SEPARATOR. 'model'. DIRECTORY_SEPARATOR;
		$this -> db = $this -> getEvent_Register()
							-> getRegistered('Cy\Db\Db');
	}
	
	public function getModel($model_name,$params = array())
	{
		if ( !file_exists($this -> model_path. $model_name. 'php') )
			 $this -> error('No such model has been found in '. $this -> model_path, 1010);
		$namespace = 'model\\'. $model_name;
		$params['model_path'] = $this -> model_path;
		return $this -> getEvent_Register()
					 -> getRegistered($namespace,$params);
	}
	
	protected function getPlugin()
	{
		$params = func_get_args();
		$plugin_name = array_shift($params);
		if ( is_array($params) && count($params) === 1)
			$params = $params[0];
		return $this -> getEvent_Register()
					 -> getRegistered('Cy\Plugin\Plugin')
					 -> getPluginObj($plugin_name,$params);
	}
	
	public function add($columns,$table = null)
	{
		if ($table === null)
			$table = $this -> table;
		$re = $this -> db -> insert($table,$columns) -> query();
		if ( $re instanceof \PDOStatement )
			return $re -> rowCount();
		return 0;
	}
	
	public function edit($columns,$where,$table = null)
	{
		if ($table === null)
			$table = $this -> table;
		$re = $this -> db -> update($table,$columns)
						  -> where($where);
		if ( $re instanceof \PDOStatement )
			return $re -> rowCount();
		return 0;
	}
	
	public function del($where,$table = null)
	{
		if ($table === null)
			$table = $this -> table;
		$re = $this -> db -> delete($table)
						  -> where($where);
		if ( $re instanceof \PDOStatement )
			return $re -> rowCount();
		return 0;
	}
}