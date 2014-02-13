<?php
namespace Cy\Mvc\Controller;
use Cy\Mvc\Event\Event;
use Cy\Mvc\Render;
use Cy\Mvc\Request;

class Controller extends Event
{
    protected $_render;
    
	public function __construct()
	{
        $this->_render = Render::initialization();
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

    /*
     * $type can be 'info','success','warning' or 'danger'!
     */
	protected function jump($type, $info,$callBackUrl)
    {
		$this->_render->isDisplay();
        $data['info'] = $info;
        $data['type'] = $type;
		$data['callBackUrl'] = (strtolower(substr($callBackUrl,0,4))=='http')? $callBackUrl:'http://'.$callBackUrl;
		$this -> assign('data', $data);
		$this->_render->setTemplateFile('public/jump.php');
	}

	protected function display($file)
	{
		$this->_render->isDisplay();
		$this->_render->setTemplateFile($file);
	}
	
	protected function assign($name,$data)
	{
		$this->_render->assign($name, $data);
	}
	
	protected function getTemplatePath()
	{
		return $this->_render->getTemplatePath();
	}
	
	protected function setTemplatePath($path)
	{
		$this->_render->setTemplatePath($path);
	}
	
	protected function getParams()
	{
		return Request :: getParams();
	}
}
