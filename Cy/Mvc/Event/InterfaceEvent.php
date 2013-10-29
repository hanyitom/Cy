<?php
namespace Cy\Mvc\Event;

interface InterfaceEvent
{
    public function register($namespace, $eventObj);
    public function getRegistered($namespace);
    public function attach($object, $func, $params = array());
    public function detach();
    public function error($message,$error_code, $trace = false, $lv = 'Notice', $previous = null);
}
