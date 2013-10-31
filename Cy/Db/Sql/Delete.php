<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\AbstractBaseSQL;

class Delete extends AbstractBaseSQL
{
	public function __construct($tablename)
	{
		parent::__construct();
		$this->_sql = 'delete from '.$tablename;
	}
	public function toString()
	{
		$sql = $this->_sql;
		if ( $this->_where != null )
			$sql .= $this->_where;
		$this->_db->_sql = $sql;
		return $this->_sql;
	}
}
