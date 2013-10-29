<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\Abstract_BaseSQL;

/**
 * 更新类sql语句
 * @author Toby
 */
class Update extends Abstract_BaseSQL
{
	/**
	 * 实例化对象
	 * @param string $tablename	表名
	 * @param array $columns	字段名与对应值的数组
	 */
	public function __construct($tablename,$columns)
	{
		parent :: __construct();
		$key = $val = $column =  array();
		foreach($columns as $key => $val)
		{
			if(is_string($val))
				$val = "'$val'";
			$column[] = $key. '='. $val; 
		}
		$c = implode(',',$column);
		$this -> sql = 'update '. $tablename. ' set '. $c;
	}
	
	/**
	 * @see library/Cy/Db/Sql/Cy\Db\Sql.BaseSQL_Abstract::__toString()
	 */
	public function toString()
	{
		$sql = $this -> sql;
		if ( $this -> where != null )
			$sql .= $this -> where;
		$this -> Db -> sql = $sql;
		return $this -> sql;
	}
}