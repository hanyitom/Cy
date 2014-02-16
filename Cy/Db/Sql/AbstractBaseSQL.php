<?php
namespace Cy\Db\Sql;
use Cy\Mvc\Event\EventRegister;
use Cy\Mvc\EventsManager;

/**
 * SQL基类
 * @author Toby
 */
abstract class AbstractBaseSQL
{
	/**
	 * sql语句
	 * @var String
	 */
	protected $_sql		=	null;
	protected $_where	=	null;
	protected $_group	=	null;
	protected $_order	=	null;
	protected $_limit	=	null;
	protected $_having	=	null;
	protected $_db;

	public function __construct()
	{
		$this->_db = EventsManager::getEventRegister()->getRegistered('Cy\Db\Db');
	}

	public function where($where)
	{
		$this->_where = ' where '. $where;
		return $this;
	}

	public function group($group, $having = null)
	{
		$this->_group = ' group by '. $group;
		if($having !== null)
			$this->_having = ' having '. $having;
		return $this;
	}

	public function order($order, $sort = 'DESC')
	{
		$this->_order = ' order by '. $order. ' '. $sort;
		return $this;
	}

	public function limit($end, $offset = null)
	{
		$limit = ' limit ';
		if ( $offset == null)
			$limit .= $end;
		else
			$limit .= $offset. ','. $end;
		$this->_limit = $limit;
		return $this;
	}

	public function query()
	{
		$this->toString();
		return $this->_db->query();
	}

	public function execute()
	{
		$this->toString();
		return $this->_db->execute();
	}

	abstract public function toString();
}
