<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\AbstractBaseSQL;

/**
 * 更新类sql语句
 * @author Toby
 */
class Update extends AbstractBaseSQL
{
	/**
	 * 实例化对象
	 * @param string $tablename	表名
	 * @param array $columns	字段名与对应值的数组
	 */
	public function __construct($tablename,$columns)
	{
		parent::__construct();
		$key = $val = $column =  array();
		foreach($columns as $key => $val)
		{
			if(is_string($val))
				$val = "'$val'";
			$column[] = $key. '='. $val; 
		}
		$c = implode(',',$column);
		$this->_sql = 'update '. $tablename. ' set '. $c;
	}

	/**
	 * @see library/Cy/Db/Sql/Cy\Db\Sql.BaseSQL_Abstract::__toString()
	 */
	public function toString()
	{
		$sql = $this->_sql;
		if ( $this->_where != null )
			$sql .= $this->_where;
		$this->_db->_sql = $sql;
		return $this->_sql;
	}
}
