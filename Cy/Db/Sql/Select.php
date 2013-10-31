<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\AbstractBaseSQL;

class Select extends AbstractBaseSQL
{
	const LEFT = 'left join';
	const RIGHT = 'right join';
	const INNER = 'inner join';
	const CROSS = 'cross join';
	protected $_join = null;

	public function join($tablename, $type)
	{
		$this->_join = $type. ' '. $tablename;
		return $this;
	}
	public function __construct($columns, $tablename)
	{
		parent::__construct();
		$this->_sql = 'select '. $columns. ' from '. $tablename;
		return $this;
	}
	public function toString()
	{
		$sql = $this->_sql;
		if ( $this->_where )
			$sql .= $this->_where;
		if ( $this->_group )
			$sql .= $this->_group;
		if ( $this->_having )
			$sql .= $this->_having;
		if ( $this->_order )
			$sql .= $this->_order;
		if ( $this->_limit )
			$sql .= $this->_limit;
		$this->_db->_sql = $sql;
		return $this->_sql;
	}
}
