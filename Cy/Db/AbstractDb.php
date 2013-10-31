<?php
namespace Cy\Db;

use Cy\Log\Log;
use Cy\Mvc\EventsManager;
use Cy\Exception\Exception;

/**
 * PDO封装类
 * @author Toby
 */
abstract class AbstractDb
{
	/**
	 * 记录执行的sql
	 */
	public $_sql;
	/**
	 * PDO对象实例
	 * @var Object of PDO
	 */
	protected $_pdo;
	/**
	 * 输出模式
	 * @var PDO常量
	 */
	protected $_fetchMode = \PDO::FETCH_ASSOC;
	/**
	 * Db配置
	 * @var Array
	 */
    protected $_dbConf;
    protected $_dbName;
	protected $_logFlag;
	protected $_prepare;

	/**
	 * 实例化对象
     * @param Array  $dbConf
     * e.g array('type'=>'mysql','host'=>'127.0.0.1','dbName'=>'test',
     * 'port'=>3306,'char'=>'utf8','user'=>'root','pass'=>'','log'=>false, 'persistent'=>false)
	 */
	protected function __construct($dbConf)
	{
		$this->_logFlag = false;
        $this->_dbConf[$dbConf['dbName']] = $dbConf;
        $this->_dbName  = $dbConf['dbName'];
        $this->initDb($dbConf);
    }

    protected function initDb($dbConf)
    {
		try
		{
			$dsn = $dbConf['type'].':host='.$dbConf['host'].';dbname='.$dbConf['dbname'];
			if ( isset($dbConf['port']) )
				$dsn .= ';port='. $dbConf['port'];
			$arr = array(\PDO::ATTR_PERSISTENT => $dbConf['persistent'],
						\PDO::ATTR_AUTOCOMMIT => true );
			$this->_pdo = new \PDO($dsn,$dbConf['user'],$dbConf['pass'],$arr);
            $this->_pdo->exec('SET NAMES '.$dbConf['char']);
			$this->_logFlag = $dbConf['log'];
		}
		catch(\PDOException $e)
		{
            EventsManager::getDi()
                ->attach(array('obj'=> new Exception($e->getMessage().' in '.$e->getFile().' on line '.$e->getLine(), 1003, true, 1),
                            'func'  => 'showException',
                            'params'=> array()));
		}
	}

    protected function checkInstance()
    {
        if ( !$this->_pdo instanceof \PDO )
			$this->initDb($this->_dbConf[$this->_dbName]);
    }
	/**
	 * 设置PDO属性
	 * @params Array $option PDO CONST
	 */
	public function setAttribute( $option )
	{
        $this->checkInstance();
		foreach($option as $v)
		{
			if ( is_array($v) )
				$this->_pdo->setAttribute($v[0],$v[1]);
            else
            {
                EventsManager::getDi()
                    ->attach(array('obj'=> new Exception('Invalid parameter been given for setting attributes to PDO', 1004, true, 1),
                                'func'  => 'showException',
                                'params'=> array()));
            }
		}
	}

	/**
	 * 获取当前的FETCH MODE
	 */
	public function getFetchMode()
	{
		return $this->_fetchModel;
	}

	/**
	 * 设置FetchMode
	 * @param PDO FETCH MODE $fetchMode
	 */
	public function setFetchMode( $fetchMode )
	{
		$this->_fetchModel = $fetchMode;
	}

	/**
	 * 获取上次执行的sql语句
	 */
	public function getLastSql()
	{
		return $this->_sql;
	}

	/**
	 * 获取上一次写入的ID
	 */
	public function getLastInsertId()
	{
        $this->checkInstance();
		return $this->_pdo->lastInsertId();
	}

	/**
	 * 预置SQL
	 * @param String $sql
	 * @param String $driver_option
	 */
	public function prepare($driver_option = array())
	{
		$this->_prepare = $this->_pdo->prepare($this->_sql,$driver_option);
		$this->dbLog('prepare');
		return $this;
	}

	/**
	 * 执行SQL并返回影响条数
	 * @param String $sql	SQL语句
	 */
	public function exec($sql)
	{
		$re = $this->_pdo->exec($sql);
		if($re)
		{
			$this->dbLog();
			return $re;
		}
        else
        {
            EventsManager::getDi()
                ->attach(array('obj'=> new Exception('Sql Error: '.$sql, 1009, true, 1),
                            'func'  => 'showException',
                            'params'=> array()));
        }
	}

	/**
	 * 返回PDO驱动数组
	 */
	public function getAvailableDrivers()
	{
        $this->checkInstance();
		return $this->_pdo->getAvailableDrivers();
	}

	/**
	 * 执行SQL
	 * @param String $sql	SQL语句
	 */
	public function query()
	{
		$re = $this->_pdo->query($this->_sql);
		if($re instanceof \PDOStatement)
		{
			$this->dbLog();
			if ( strpos('select',$this->_sql) === 0 )
				return $re;
			return $re->fetchAll($this->_fetchMode);
		}
		else
		{
            $e = $this->_pdo->errorInfo();
            EventsManager::getDi()
                ->attach(array('obj'=> new Exception($e[2], 1009, true, 1),
                            'func'  => 'showException',
                            'params'=> array()));
		}
	}

	/**
	 * 开启事件
	 */
	public function beginTransaction()
	{
		if ( $this->inTransaction() )
			return false;
        $this->checkInstance();
		$this->setAttribute(array(\PDO::ATTR_AUTOCOMMIT,false));
		$this->_pdo->beginTransaction();
		return $this;
	}

	/**
	 * 开启自动登录
	 * 成功开启返回true。若已经是自动提交返回false
	 */
	public function setAutoCommit()
	{
		if( $this->inTransaction() )
			$this->setAttribute(array(\PDO ::ATTR_AUTOCOMMIT,true));
		else
			return false;
		return true;
	}

	/**
	 * 提交
	 */
	public function commit()
	{
        $this->checkInstance();
		$this->_pdo->commit();
	}

	/**
	 * 回滚
	 */
	public function rollback()
	{
        $this->checkInstance();
		$this->dbLog('rollback');
		$this->_pdo->rollback();
	}

	/**
	 * 判断是否开启了事务
	 */
	public function inTransaction()
	{
		return $this->_pdo->inTransaction();
	}

	/**
	 * 添加单引号
	 * @param String $string
	 */
	public function quote( $string )
	{
		return $this->_pdo->quote($string);
	}

	protected function dbLog($type = null, $ex = null)
	{
		$this->_logFlag = true;
		if ( $type === null || $type == 'prepare' )
		{
			$type = substr($this->_sql, 0, strpos($this->_sql,' ')-1);
			$sql = $this->_sql;
		}
		else
		{
			if ( $ex === null)
				$sql = '';
			else
				$sql = $ex;
		}
        EventsManager::getEventRegister()
            ->getRegistered('Cy\Log\LogManager')
			->db()
			->message($sql, $type)
			->add();
	}

    public function resetDB($dbName, $dbConf = null)
	{
        if ($this->_dbConf !== null && (!isset($this->_dbConf[$dbName]) || $this->_dbConf[$dbName] != $dbConf))
        {
            $this->_dbName          = $dbName;
            $this->_dbConf[$dbName] = $dbConf;
            $this->initDb($dbConf);
            return $this;
        }
        if ($this->_dbName != $dbName)
        {
            if (isset($this->_dbConf[$dbName]))
                $this->initDb($this->_dbConf[$dbName]);
            else
            {
                EventsManager::getDi()
                    ->attach(array('obj'=> new Exception('No configure for database '.$dbName, 1015, true, 1),
                                'func'  => 'showException',
                                'params'=> array()));
            }
        }
		return $this;
	}
	abstract public function __call($method, $params);
	abstract public function setDb($DbName);
	abstract public function getOne();
	abstract public function getAll();
}
?>
