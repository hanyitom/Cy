<?php
namespace Cy\Mvc;

use Cy\Mvc\EventsManager;
use Cy\Exception\Exception;

class Render
{
	public static $_templatePath    =   TEMPLATE;
	public static $_templateFile    =   '';
	public static $_data            =   array();
	public static $_isDisplay       =   false;

	public static function initialization()
	{
		self::$_isDisplay   = false;
		self::$_data        = array();
		self::$_templatePath= TEMPLATE;
		EventsManager::getDi()->detach();
        EventsManager::getDi()
            ->attach(array('obj'    => new self(),
                        'func'      => 'display',
                        'params'    => array()));
	}

	public function display()
	{
		if ( self::$_isDisplay )
		{
			if( !empty(self::$_data) )
			{
				foreach(self::$_data as $k => $v)
					$$k = $v;
			}

			$path = self::$_templatePath.self::$_templateFile;
			if ( file_exists($path) )
				require_once($path);
			else
                EventsManager::getDi()
                    ->attach(array('obj'=> new Exception('No such template file '.$path.' been found!', 1002, true),
                                'func'  =>'showException',
                                'params'=> array()));
		}
	}

	public static function setTemplateFile($file)
	{
		self::$template_file = $file;
	}

	public static function getTemplatePath()
	{
		return self::$template_path;
	}

	public static function setTemplatePath($path)
	{
		self::$template_path = $path;
	}

	public static function assign($name,$data)
	{
		self::$data[$name] = $data;
	}

	public static function isDisplay()
	{
		self::$isDisplay = true;
	}
}
