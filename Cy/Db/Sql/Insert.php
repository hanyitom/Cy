<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\AbstractBaseSQL;

class Insert extends AbstractBaseSQL
{
	public function __construct($tablename,$columns)
	{
		parent::__construct();
		$column = $value = array();
		foreach($columns as $key => $val)
		{
			$column[] = $key;
			$value[] = is_string($val) ? "'$val'" : $val; 
		}
		$c = implode(',',$column);
		$v = implode(',',$value);
		$this->_sql = 'insert into '. $tablename. '('. $c. ') values('. $v. ')';
	}

	public function toString()
	{
		$this->_db->_sql = $this->_sql;
		return $this->_sql;
	}
}
