<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\Abstract_BaseSQL;

class Insert extends Abstract_BaseSQL
{
	public function __construct($tablename,$columns)
	{
		parent :: __construct();
		$column = $value = array();
		foreach($columns as $key => $val)
		{
			$column[] = $key;
			$value[] = is_string($val) ? "'$val'" : $val; 
		}
		$c = implode(',',$column);
		$v = implode(',',$value);
		$this -> sql = 'insert into '. $tablename. '('. $c. ') values('. $v. ')';
	}
	
	public function toString()
	{
		$this -> Db -> sql = $this -> sql;
		return $this -> sql;
	}
}