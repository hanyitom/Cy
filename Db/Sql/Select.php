<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\Abstract_BaseSQL;

class Select extends Abstract_BaseSQL
{
	const LEFT = 'left join';
	const RIGHT = 'right join';
	const INNER = 'inner join';
	const CROSS = 'cross join';
	protected $join = null;
	
	public function join($tablename, $type)
	{
		$this -> join = $type. ' '. $tablename;
		return $this;
	}
	public function __construct($columns, $tablename)
	{
		parent :: __construct();
		$this -> sql = 'select '. $columns. ' from '. $tablename;
		return $this;
	}
	public function toString()
	{
		$sql = $this -> sql;
		if ( $this -> where )
			$sql .= $this -> where;
		if ( $this -> group )
			$sql .= $this -> group;
		if ( $this -> having )
			$sql .= $this -> having;
		if ( $this -> order )
			$sql .= $this -> order;
		if ( $this -> limit )
			$sql .= $this -> limit;
		$this -> Db -> sql = $sql;
		return $this -> sql;
	}
}