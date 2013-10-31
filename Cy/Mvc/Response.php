<?php
namespace Cy\Mvc;

use Cy\Mvc\EventsManager;
use Cy\Mvc\Controller\Controller;

class Response
{
	private $_router;

	public function __construct($router)
	{
		$this->_router = $router;
		EventsManager::getDi()->detach();
        EventsManager::getDi()
            ->attach(array('obj'=>  $this,
                        'func'  =>  'getResult',
                        'params'=>  array()));
	}

	public function getResult()
	{
        $_router->endRouter();
    }
}
