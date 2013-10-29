<?php
namespace Cy\Db\Sql;
use Cy\Db\Sql\Abstract_BaseSQL;

class Delete extends Abstract_BaseSQL
{
	public function __construct($tablename)
	{
		parent :: __construct();
		$this -> sql = 'delete from '.$tablename;
	}
	public function toString()
	{
		$sql = $this -> sql;
		if ( $this -> where != null )
			$sql .= $this -> where;
		$this -> Db -> sql = $sql;
		return $this -> sql;
	}
}