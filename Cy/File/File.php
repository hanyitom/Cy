<?php
namespace Cy\File;

use Cy\Mvc\EventsManager;
use Cy\Exception\Exception;

/**
 * 文件处理对象
 * @author Toby
 */
class File
{
	private $_path    = '';				//当前处理文件夹的路径
	private $_file    = array();			//当前指向文件夹下的文件
	private $_dir     = array();			//当前指向文件夹下的文件夹
	private $_all     = array();			//当前指向文件夹下的所有文件和文件夹

	public function __construct( $path )
	{
		$this->_path = $path;
		if ( is_dir($path) )
			$tmp = scandir($path);
		else
//			$this -> error('No such path "'.$path.'" has been found in '. $e -> getFile(). ' on line '. $e -> getLine(). '!',1008);
            EventsManager::getDi()
                ->attach(array('obj'=>new Exception('Invalid path '.$path.' been given!', 1008, true, 1),
                            'func'  => 'showException',
                            'params'=> array()));
		foreach( $tmp as $k=>$v )
		{
            if ( is_dir($path.DIR_S.$v))
            {
                if($v != '.' || $v != '..')
                    $this->_dir[] = $v;
                else
                    unset($tmp[$k]);
            }
			else
				$this->_file[] = $v;
		}
        $this->_all=$tmp;
        unset($tmp);
	}

	public function getFiles()
	{
		return $this->_file;
	}

	public function getDirs()
	{
		return $this->_dir;
	}

	public function getAllFiles()
	{
		return $this->_all;
	}
}
