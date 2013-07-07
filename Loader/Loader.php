<?php
namespace Cy\Loader;

require_once 'Loader_Exception.php';

/**
 * 类加载类。
 * @author Toby
 *
 */
class Loader
{
	/**
	 * 通过指定命名空间引入类文件
	 * @param String $namespace
	 */
	public static function loadClass($namespace)
	{
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
		try
		{
			require_once $path. '.php';	
		}catch(Loader_Exception $e)
		{
			die('No such file '. $path);
		}
	}
	
	/**
	 * 自动加载类文件
	 * @param String $class
	 */
	public static function autoLoad($namespace)
	{
		var_dump($namespace);
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
		try
		{
			if ( file_exists(Cy_ROOT. $path. '.php') ) 
				require_once $path. '.php';
			else if ( file_exists( ROOT . $path. '.php' ) )
				require_once ROOT . $path. '.php';
			else
				throw new \Exception('No such Class "'. $path .'" has been found! <b>ErrorCode:</b>',1001);
		}
		catch (\Exception $e)
		{
			die($e -> getMessage(). $e -> getCode());
		}
	}
}
?>