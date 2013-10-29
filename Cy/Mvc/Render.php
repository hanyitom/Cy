<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;

class Render extends Event
{
	public static $template_path;
	public static $template_file;
	public static $data;
	public static $isDisplay;
	
	public function __construct($response,$template_path)
	{
		parent :: __construct();
		$this -> response = $response;
		self :: $isDisplay = false;
		self :: $data = array();
		$this -> getDi() -> detach();
		self :: $template_path = $template_path;
		$this -> attach($this, 'display');
	}
	
	public function display()
	{
		if ( self :: $isDisplay )
		{
			if( !empty(self::$data) )
			{
				foreach(self :: $data as $k => $v)
					$$k = $v;
			}
			$path = self :: $template_path . self :: $template_file;
			if ( file_exists($path) )
				require_once(self :: $template_path . self :: $template_file);
			else
				$this -> error('No found such file!', 1002);
		}
	}
	
	public static function setTemplateFile($file)
	{
		self :: $template_file = $file;
	}
	
	public static function getTemplatePath()
	{
		return self :: $template_path;
	}
	
	public static function setTemplatePath($path)
	{
		self :: $template_path = $path;
	}
	
	public static function assign($name,$data)
	{
		self :: $data[$name] = $data;
	}
	
	public static function isDisplay()
	{
		self :: $isDisplay = true;
	}
}
