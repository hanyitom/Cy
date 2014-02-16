<?php
namespace Cy\Mvc\Controller;
use Cy\Mvc\Event\Event;
use Cy\Mvc\Render;
use Cy\Mvc\Request;
use Cy\Mvc\EventsManager;

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
		return $this -> getEventRegister()
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
        $callBackUrl = ($callBackUrl[0]=='/')? $_SERVER['HTTP_HOST'].$callBackUrl:$callBackUrl;
		$data['callBackUrl'] = (strtolower(substr($callBackUrl,0,4))=='http')? $callBackUrl:'http://'.$callBackUrl;
        $this->assign('data', $data);
		$this->_render->setTemplateFile('public/jump.php');
        EventsManager::getDi()->detach();
	}

	protected function display($file)
	{
		$this->_render->isDisplay();
		$this->_render->setTemplateFile($file);
        EventsManager::getDi()->detach();
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
