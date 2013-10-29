<?php
namespace Cy\Db;

use Cy\Mvc\Event\Event;
use Cy\Log\Log;
use Cy\Mvc\EventsManager;

/**
 * PDO封装类
 * @author Toby
 */
abstract class AbstractDb extends Event
{
	/**
	 * 记录执行的sql
	 */
	public $sql;
	/**
	 * PDO对象实例
	 * @var Object of PDO
	 */
	protected $PDO;
	/**
	 * 输出模式
	 * @var PDO常量
	 */
	protected $fetch_mode = \PDO :: FETCH_ASSOC;
	/**
	 * Db配置
	 * @var Array
	 */
	protected $DBconfig;
	protected $Log_Flag;
	protected $prepare;

	/**
	 * 实例化对象
	 * @param Array $DBconfig
	 */
	public function __construct()
	{
		parent::__construct();
		$this->Log_Flag = false;
		$Base_Config_Info = $this -> getEvent_Register() -> getRegistered('Cy\Config\Config') -> getConfig('BASE_INFO');
		$this -> DBconfig = $Base_Config_Info['Base_Db'];
		$this -> initDB();
	}

	/**
	 * 初始化
	 */
	protected function initDB()
	{
		try
		{
			$dsn = $this -> DBconfig['type']. ':host='. $this -> DBconfig['host']. ';dbname='. $this -> DBconfig['dbname'];
			if ( isset($this -> DBconfig['port']) )
				$dsn .= ';port='. $this -> DBconfig['port'];
			$arr = array(\PDO::ATTR_PERSISTENT => false,
						\PDO::MYSQL_ATTR_INIT_COMMAND => 'set names '. $this ->DBconfig['char'],
						\PDO::ATTR_AUTOCOMMIT => true );
			if ( $this -> DBconfig['persistent'] )
				$arr[\PDO::ATTR_PERSISTENT] = true;
			$this -> PDO = new \PDO($dsn,$this -> DBconfig['user'],$this -> DBconfig['pass'],$arr);
			if (isset($this -> DBconfig['Log_Flag']) && $this -> DBconfig['Log_Flag'])
				$this -> Log_Flag = true;
		}
		catch(\PDOException $e)
		{
			$this -> error($e -> getMessage(). 'in File '. $e -> getFile(). ' on line '. $e -> getLine(), 1003);
		}
	}

	/**
	 * 设置PDO属性
	 * @params Array $option PDO CONST
	 */
	public function setAttribute( $option )
	{
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		foreach($option as $v)
		{
			if ( is_array($v) )
				$this -> PDO -> setAttribute($v[0],$v[1]);
			else
				$this -> error('Error parameter has been given in DB attribute setting!',1004);
		}
	}

	/**
	 * 获取当前的FETCH MODE
	 */
	public function getFetchMode()
	{
		return $this -> fetch;
	}

	/**
	 * 设置FetchMode
	 * @param PDO FETCH MODE $fetchMode
	 */
	public function setFetchMode( $fetchMode )
	{
		$this -> fetch = $fetchMode;
	}

	/**
	 * 获取上次执行的sql语句
	 */
	public function getLastSql()
	{
		return $this -> sql;
	}

	/**
	 * 获取上一次写入的ID
	 */
	public function getLastInsertId()
	{
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		return $this -> PDO -> lastInsertId();
	}

	/**
	 * 预置SQL
	 * @param String $sql
	 * @param String $driver_option
	 */
	public function prepare($driver_option = array())
	{
		$this -> prepare = $this -> PDO -> prepare($this -> sql,$driver_option);
		$this -> Db_Log('prepare');
		return $this;
	}

	/**
	 * 执行SQL并返回影响条数
	 * @param String $sql	SQL语句
	 */
	public function exec($sql)
	{
		$re = $this -> PDO -> exec($sql);
		if($re)
		{
			$this -> Db_Log();
			return $re;
		}
		else
			$this -> error('Sql Error for "'.$sql.'"!', 1009);
	}

	/**
	 * 返回PDO驱动数组
	 */
	public function getAvailableDrivers()
	{
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		return $this -> PDO -> getAvailableDrivers();
	}

	/**
	 * 执行SQL
	 * @param String $sql	SQL语句
	 */
	public function query()
	{
		$re = $this -> PDO -> query($this -> sql);
		if($re instanceof \PDOStatement)
		{
			$this -> Db_Log();
			if ( strpos('select',$this -> sql) === 0 )
				return $re;
			return $re -> fetchAll($this -> fetch_mode);
		}
		else
		{
			$e = $this -> PDO -> errorInfo();
			$this -> error($e[2], 1009);
		}
	}

	/**
	 * 开启事件
	 */
	public function beginTransaction()
	{
		if ( $this -> inTransaction() )
			return false;
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		$this -> setAttribute(array(\PDO::ATTR_AUTOCOMMIT,false));
		$this -> PDO -> beginTransaction();
		return $this;
	}

	/**
	 * 开启自动登录
	 * 成功开启返回true。若已经是自动提交返回false
	 */
	public function setAutoCommit()
	{
		if( $this -> inTransaction() )
			$this -> setAttribute(array(\PDO ::ATTR_AUTOCOMMIT,true));
		else
			return false;
		return true;
	}

	/**
	 * 提交
	 */
	public function commit()
	{
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		$this -> PDO -> commit();
	}

	/**
	 * 回滚
	 */
	public function rollback()
	{
		if( !$this -> PDO instanceof \PDO )
			$this -> initDB();
		$this -> Db_Log('rollback');
		$this -> PDO -> rollback();
	}

	/**
	 * 判断是否开启了事务
	 */
	public function inTransaction()
	{
		return $this -> PDO -> inTransaction();
	}

	/**
	 * 添加单引号
	 * @param String $string
	 */
	public function quote( $string )
	{
		return $this -> PDO -> quote($string);
	}

	protected function Db_Log($type = null, $ex = null)
	{
		$this -> Log_Flag = true;
		if ( $type === null || $type == 'prepare' )
		{
			$type = substr($this -> sql,0, strpos($this -> sql,' ')-1);
			$sql = $this -> sql;
		}
		else
		{
			if ( $ex === null)
				$sql = '';
			else
				$sql = $ex;
		}
		Events_Manager :: getEvent_Register() -> getRegistered('Cy\Log\Log_Manager')
											  -> Db()
											  -> message($sql, $type)
											  -> add();
	}

	abstract public function resetDB( $DBconfig );
	abstract public function __call($method, $params);
	abstract public function setDb($DbName);
	abstract public function getOne();
	abstract public function getAll();
}
?>
