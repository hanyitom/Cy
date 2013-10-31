<?php
namespace Cy\Db;

use Cy\Db\AbstractDb;
use Cy\Db\Sql\Insert;
use Cy\Db\Sql\Update;
use Cy\Db\Sql\Select;
use Cy\Db\Sql\Delete;

/**
 * 数据库操作类(PDO)
 * @author Toby
 */
class Db extends AbstractDb
{
	private $_METHODS = array('Insert','Update','Select','Delete');

    public static function getInstance($dbConf)
    {
        return new self($dbConf);
    }

	public function __call($method,$params)
	{
		$method = ucfirst(strtolower($method));
		if ( !in_array($method,$this->_METHODS) )
			return false;
		$method = 'Cy\Db\Sql\\'.$method;
		if ( isset($params[1]) )
			return new $method($params[0],$params[1]);
		else
			return new $method($params[0]);
	}

	public function setDb($DbName)
	{
		$this->query('use '.$DbName);
	}

	public function getOne()
	{
		$this->setFetchMode(\PDO::FETCH_NUM);
		$re = $this->query()
				   ->fetchAll();
		return $re[0];
	}

	public function getAll()
	{
		$this->setFetchMode(\PDO::FETCH_ASSOC);
		return $this->query()
					->fetchAll();
	}
}
