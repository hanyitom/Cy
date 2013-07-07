<?php
namespace Cy\Mvc\Controller;
use Cy\Mvc\Event\Event;
use Cy\Mvc\View;
use Cy\Mvc\Request;

class Controller extends Event
{
	public function __construct()
	{
		parent :: __construct();
		
		$this -> init();
	}
	
	public function init(){}
	
	protected function getModel($model_name)
	{
		return $this -> getEvent_Register()
					 -> getRegistered('Cy\Mvc\Model\Model')
					 -> getModel($model_name);
	}
	
	public function display($file)
	{
		View :: isDisplay();
		View :: setTemplateFile($file);
	}
	
	public function jump($info,$callBackUrl)
	{
		View :: isDisplay();
		$data['info'] = $info;
		$data['callBackUrl'] = 'http://'.$callBackUrl;
		$this -> assign('data', $data);
		View :: setTemplateFile('public/jump.php');
		
	}
	
	public function assign($name,$data)
	{
		View :: assign($name, $data);
	}
	
	public function getTemplatePath()
	{
		return View :: getTemplatePath();
	}
	
	public function setTemplatePath($path)
	{
		View :: setTemplatePath($path);
	}
	
	public function getParams()
	{
		return Request :: getParams();
	}
}