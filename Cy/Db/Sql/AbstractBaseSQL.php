<?php
namespace Cy\Db\Sql;
use Cy\Mvc\Event\Event_Register;
use Cy\Mvc\Events_Manager;

/**
 * SQL基类
 * @author Toby
 */
abstract class Abstract_BaseSQL
{
	/**
	 * sql语句
	 * @var String
	 */
	protected $sql		=	null;
	protected $where	=	null;
	protected $group	=	null;
	protected $order	=	null;
	protected $limit	=	null;
	protected $having	=	null;
	protected $Db;
	
	public function __construct()
	{
		$this -> Db = Events_Manager :: getEvent_Register() -> getRegistered('Cy\Db\Db');
	}
	
	public function where($where)
	{
		$this -> where = ' where '. $where;
		return $this;
	}
	
	public function group($group, $having = null)
	{
		$this -> group = ' group by '. $group;
		if(having !== null)
			$this -> having = ' having '. $having;
		return $this;
	}
	
	public function order($order, $sort = 'DESC')
	{
		$this -> order = ' order by '. $order. ' '. $sort;
		return $this;
	}
	
	public function limit($end, $offset = null)
	{
		$limit = ' limit ';
		if ( $offset == null)
			$limit .= $end;
		else
			$limit .= $offset. ','. $end;
		$this -> limit = $limit;
		return $this;
	}
	
	public function query()
	{
		$this -> toString();
		return $this -> Db -> query();
	}
	
	public function execute()
	{
		$this -> toString();
		return $this -> Db -> execute();
	}
	
	abstract public function toString();
}