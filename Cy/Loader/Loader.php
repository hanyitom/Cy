<?php
namespace Cy\Loader;

require_once 'LoaderException.php';

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
        $path = ROOT.str_replace('\\', DIR_S, $namespace);
        if (file_exists($path.'.php'))
	        require_once $path.'.php';
        else
            throw new LoaderException('No such file '.$path.'.php been found! <b>ErrorCode:</b>', 1001, true);
	}

	/**
	 * 自动加载类文件
	 * @param String $class
	 */
	public static function autoLoad($namespace)
    {
        try
        {
            self::loadClass($namespace);
        }
        catch(LoaderException $e)
        {
            $e->showException();
        }
	}
}
?>
