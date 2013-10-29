<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;
use Cy\Mvc\Controller\Controller;

class Response extends Event 
{
	private $_router;

	public function __construct($router)
	{
		parent::__construct();
		$this->_router = $router;
		$this->getDi() -> detach();
		$this->attach( $this, 'getResult');
	}

	public function getResult()
	{

    }
}
