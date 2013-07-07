<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;

class Request extends Event
{
//	private $REMOTE_PORT;
//	private $REMOTE_ADDR;
//	private $HTTP_USER_AGENT;
//	private $HTTP_COOKIE;
//	private $REQUEST_URI;
//	private $REQUEST_TIME;
	private $REQUEST_CLASS	=	'index';
	private $REQUEST_ACTION	=	'index';
	protected static $REQUEST_PARAMS	=	array();
//	private $HTTP_HOST;
	
	public function __construct()
	{
		parent::__construct();
		$this -> getDi() -> detach();
//		$this -> prepare();
		$this -> attach( $this, 'parseRequest');
	}
	
//	private function prepare()
//	{
//		$this -> HTTP_HOST = $_SERVER['HTTP_HOST'];
//		$this -> REMOTE_PORT = $_SERVER['REMOTE_PORT'];
//		$this -> REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
//		$this -> HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
//		$this -> REQUEST_URI = $_SERVER['REQUEST_URI'];
//		$this -> REQUEST_TIME = $_SERVER['REQUEST_TIME'];
//		self :: $REQUEST_PARAMS = array();
//		$this -> REQUEST_CLASS = 'index';
//		$this -> REQUEST_ACTION = 'index';
//		$this -> GET = $_GET;
//		$this -> POST = $_POST;
//		unset($_POST,$_SERVER,$_GET);
//	}
	
	public function parseRequest()
	{
		$uri = substr( $this -> REQUEST_URI, 1);
		$tmp = explode('/',$uri);
		if ( $tmp1 = array_shift($tmp) )
		{
			$this -> REQUEST_CLASS = $tmp1;
			if( $tmp1 = array_shift($tmp) )
			{
				$this -> REQUEST_ACTION = $tmp1;
				self :: $REQUEST_PARAMS = $tmp;
			}	
		}
		unset($tmp,$tmp1);
	}
	
/*	public function getUserIp()
	{
		return $this -> REMOTE_ADDR;
	}
	
	public function getUserSystem()
	{
		return $this -> HTTP_USER_AGENT;
	}
	
	public function getUserPort()
	{
		return $this -> REMOTE_PORT;
	}
	
	public function getRequestTime()
	{
		return $this -> REQUEST_TIME;
	}
	*/
	public function getClass()
	{
		return $this -> REQUEST_CLASS;
	}
	
	public static function getParams()
	{
		return self :: $REQUEST_PARAMS;
	}
	/*
	public function getPost()
	{
		return $this -> POST;
	}
	
	public function getGet()
	{
		return $this -> GET;
	}
	*/
	public function getAction()
	{
		return $this -> REQUEST_ACTION;
	}
	
//	public function getHttpHost()
//	{
//		return $this -> HTTP_HOST;
//	}
}