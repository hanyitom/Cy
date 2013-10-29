<?php
namespace Cy\File;
use Cy\Mvc\Event\Event;

/**
 * 文件处理对象
 * @author Toby
 */
class File extends Event
{
	protected $path = '';				//当前处理文件夹的路径
	protected $file = array();			//当前指向文件夹下的文件
	protected $dir = array();			//当前指向文件夹下的文件夹
	protected $all = array();			//当前指向文件夹下的所有文件和文件夹
	
	public function __construct( $path )
	{
		$this -> path = $path;
		if ( is_dir($path) )
			$tmp = scandir($path);
		else
			$this -> error('No such path "'.$path.'" has been found in '. $e -> getFile(). ' on line '. $e -> getLine(). '!',1008);
		foreach( $tmp as $v )
		{
			if ( is_dir($path.DIRECTORY_SEPARATOR.$v) && $v != '.' && $v != '..' )
				$this -> dir[] = $v;
			else if ( is_file($v) )
				$this -> file[] = $v;
		}
	}
	
	public function getFiles()
	{
		return $this -> file;
	}
	
	public function getDirs()
	{
		return $this -> dir;
	}
	
	public function getAllFiles()
	{
		if ( empty($this -> dir) )
			return $this -> file;
		$this -> all = array_merge($this -> file, $this -> dir);
		$tmp = $this -> dir;
		while ( !empty($tmp) )
		{
			$tmp2 = array();
			foreach ( $tmp as $v )
			{
				$basePath = $this -> path. DIRECTORY_SEPARATOR. $v;
				$tmp1 = scandir($basePath);
				foreach ( $tmp1 as $val)
				{
					if ( $val == '.' || $val == '..')
						continue;
					if ( is_dir( $basePath. DIRECTORY_SEPARATOR. $val ) )
						$tmp2[] = $v. DIRECTORY_SEPARATOR. $val;
					else if (is_file( $basePath. DIRECTORY_SEPARATOR. $val ))
						$this -> all[] = $v. DIRECTORY_SEPARATOR. $val;
				}
			}
			$tmp = $tmp2;
		}
		return $this -> all;
	}
	
	public function dir_exists( $file_dir )
	{
		return in_array($file_dir, $this -> dir);
	}
}