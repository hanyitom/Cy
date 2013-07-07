<?php
namespace Cy\Log;
use Cy\Log\Abstract_Log;

class Db_Log extends Abstract_Log
{
	protected function __construct($log_path, $log_type)
	{
		parent :: __construct($log_path, $log_type);
		$this -> log_path .= DIRECTORY_SEPARATOR. 'Db'. DIRECTORY_SEPARATOR;
		if ( !is_dir($this -> log_path) )
			mkdir($this -> log_path, true);
	}
	
	public static function getInstance($log_path, $log_type)
	{
		return new self($log_path, $log_type);
	}
	
	protected function dbMessage($params)
	{
		$this -> log_message = array('date' => $_SERVER['REQUEST_TIME'],'sql'=> $params[0], 'type'=> $params[1]);
		return $this;
	}
	
	protected function fileMessage($params)
	{
		$ex = strtoupper($params[1]);
		$this -> log_message = '['.date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']).'] '.$ex.': '. $params[0]. " \r\n";
		return $this;
	}
	protected function fileAdd()
	{
		$fileName = date('Y-m-d',$_SERVER['REQUEST_TIME']). '-Db_Log.log';
		$path = $this -> log_path. $fileName;
		if ( file_exists( $path ) )
			$source = fopen($path, 'a');
		else
			$source = fopen($path, 'w+');
		fwrite($source, $this -> log_message);
		fclose($source);
		return true;
	}
	
	protected function dbAdd()
	{
		try{
			$re = $this -> getRegistered('Cy\Db\Db')
				  		-> insert('Db_Log',$this -> log_message)
				  		-> query();
		}
		catch(\Exception $e)
		{
			return false;
		}
		return $re;
	}
	
	protected function dbGet($date)
	{
		try{
			$re = $this -> getRegistered('Cy\Db\Db')
				  		-> select('Db_Log','id,error_info,lv,date')
				  		-> query();
		}
		catch(\Exception $e)
		{
			return false;
		}
		return $re;
	}
	
	protected function fileGet($date)
	{
		$fileName = date('T-m-d',$_SERVER['REQUEST_TIME']). '-Db_Log.log';
		$path = $this -> log_path. $fileName;
		if ( file_exists( $path ) )
			$re = file_get_contents($path);
		else
			return null;
		return $re;
	}
	
	protected function dbDel($id)
	{
		if ( is_int($id) )
		{
			$this -> del($id);
		}
		else if (is_array($id))
		{
			foreach($id as $v)
				$this -> del($v);
		}
	}
	
	private function del($id)
	{
		$this -> getReigstered('Cy\Db\Db')
			  -> delete('Db_Log')
			  -> where('id='.$id)
			  -> query();
	}
}