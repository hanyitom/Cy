<?php
namespace Cy\Plugin\Session;

/**
 * Session
 * @author Toby
 */
class Session
{
	private $memcache;
	private	$is_multi_server;
	private	$memcached;
	
	public function open($session_name, $type = 'memcached', $options = array())
	{
		//设置session的名字
		session_name($session_name);
		//获取session memcached的
		if ( empty($option) )
		{
			$option	=	Events_Manager	::	getEvent_Register()
										->	getRegistered('Cy\Config\Config')
										->	getConfig($session_name);	
		}
		$this	->	memcached		=	new	Memcached($option['connect']);
		$this	->	is_multi_server	=	$option['is_multi_server'];
		$func	=	'addServer';
		if	($is_multi_server)
			$func	.=	's';
		$this	->	memcached		->	$func($session_info);
	}
	
	public function close()
	{
		return true;
	}
	
	public function write($sessid,$data)
	{
		if	($this	->	is_multi_server)
		{
			return	$this	->	memcached
							->	setByKey($sessid,$sessid,$data);
		}
		else
		{
			return	$this	->	memcached
							->	set($sessid,$data);
		}
	}
	
	public function read($sessid)
	{
		if	($this	->	is_multi_server)
		{
			return	$this	->	memcached
							->	getByKey($sessid,$sessid,$data);
		}
		else
		{
			return	$this	->	memcached
							->	get($sessid,$data);
		}
	}
	
	public function destroy($sessid)
	{
		if	($this	->	is_multi_server)
		{
			return	$this	->	memcached
							->	deleteByKey($sessid,$sessid,$data);
		}
		else
		{
			return	$this	->	memcached
							->	delete($sessid,$data);
		}
	}
	
	public function gc()
	{
		$this	->	memcached
				->	flush();
		return true;
	}
}