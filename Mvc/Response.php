<?php
namespace Cy\Mvc;
use Cy\Mvc\Event\Event;
use Cy\Mvc\Controller\Controller;

class Response extends Event 
{
	private $router;
	
	public function __construct($router)
	{
		parent :: __construct();
		$this -> router = $router;
		$this -> getDi() -> detach();
		$this -> attach( $this, 'getResult');
	}

	public function getResult()
	{
		$this -> router -> endRouter();
	}
}