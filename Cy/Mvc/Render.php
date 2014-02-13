<?php
namespace Cy\Mvc;

use Cy\Mvc\EventsManager;
use Cy\Exception\Exception;

class Render
{
	private $_templatePath    =   TEMPLATE;
	private $_templateFile    =   '';
	private $_data            =   array();
	private $_isDisplay       =   false;

	public static function initialization()
	{
        return new self;
    }

    private function __construct(){
        $this->_isDisplay   = false;
		$this->_data        = array();
		$this->_templatePath= TEMPLATE;
		EventsManager::getDi()->detach();
        EventsManager::getDi()
            ->attach(array('obj'    => $this,
                        'func'      => 'display',
                        'params'    => array()));
    }

	public function display()
	{
		if ( $this->_isDisplay )
		{
			if( !empty($this->_data) )
			{
				foreach($this->_data as $k => $v)
					$$k = $v;
			}

			$path = $this->_templatePath.$this->_templateFile;
			if ( file_exists($path) )
				require_once($path);
			else
                EventsManager::getDi()
                    ->attach(array('obj'=> new Exception('No such template file '.$path.' been found!', 1002, true),
                                'func'  =>'showException',
                                'params'=> array()));
		}
	}

	public function setTemplateFile($file)
	{
		$this->_templateFile = $file;
	}

	public function getTemplatePath()
	{
		return $this->_templatePath;
	}

	public function setTemplatePath($path)
	{
		$this->_templatePath = $path;
	}

	public function assign($name,$data)
	{
		$this->_data[$name] = $data;
	}

	public function isDisplay()
	{
		$this->_isDisplay = true;
	}
}
