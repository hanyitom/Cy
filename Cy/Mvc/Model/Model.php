<?php
namespace Cy\Mvc\Model;
use Cy\Mvc\Event\Event;

class Model extends Event
{
	protected $_modelPath   =   MODEL;
	protected $_db          =   null;
    protected $_table       =   '';

	public function __construct()
	{
        parent::__construct();
        $this->_table       =   '';
        $this->_modelPath   =   MODEL;
        $conf = $this->getRegistered('Cy\Config\Config')->getConfig('baseConf');
        $this->_db          =   $this->getRegistered('Cy\Db\Db', $conf['db']);
        unset($conf);
	}

	public function getModel($modelName, $params = array())
	{
		if ( !file_exists($this->_modelPath.$modelName.'.php') )
			 $this->error('No such model '.$modelName.' found in '.$this->_modelPath, 1010, true);
		$namespace = 'model\\'. $modelName;
		return $this->getEventRegister()
					->getRegistered($namespace, $params);
	}

	protected function getPlugin($pluginName, $params)
	{
		return $this->getEventRegister()
					->getRegistered('Cy\Plugin\Plugin')
					->getPluginObj($pluginName, $params);
	}

	public function add($columns, $table = null)
	{
		if ($table === null)
			$table = $this->_table;
		$re = $this->_db->insert($table, $columns)->query();
		if ( $re instanceof \PDOStatement )
			return $re->rowCount();
		return 0;
	}

	public function edit($columns, $where, $table = null)
	{
		if ($table === null)
			$table = $this->_table;
		$re = $this->_db->update($table, $columns)
						->where($where);
		if ( $re instanceof \PDOStatement )
			return $re->rowCount();
		return 0;
	}

	public function del($where, $table = null)
	{
		if ($table === null)
			$table = $this->_table;
		$re = $this->_db->delete($table)
						->where($where);
		if ( $re instanceof \PDOStatement )
			return $re->rowCount();
		return 0;
	}
}
