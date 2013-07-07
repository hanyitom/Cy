<?php
namespace Cy\Mvc\Event;

interface Interface_Event
{	
	function register($namespace,$eventObj);
	function getRegistered($namespace);
	function attach($object, $func, $params = array());
	function detach();
	function error($message,$error_code, $lv = 'Notice', $trace = false,$previous = null);
}