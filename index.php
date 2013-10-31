<?php
use Cy\Loader\Loader;
use Cy\Mvc\EventsManager;

define ('DIR_S',        DIRECTORY_SEPARATOR);
define ('ROOT',         dirname(__FILE__).DIR_S);
define ('CONF',         ROOT.'conf'.DIR_S);
define ('CONTROLLER',   ROOT.'controller'.DIR_S);
define ('MODEL',        ROOT.'model'.DIR_S);
define ('TEMPLATE',     ROOT.'template'.DIR_S);
define ('LOG',          ROOT.'log'.DIR_S);
define ('PLUGIN',       ROOT.'plugin'.DIR_S);
define ('CY',           ROOT.'Cy'.DIR_S);
define ('SESSID',       'PHPSESSID');
define ('DEBUG',        false);
define ('LANGUAGE',     'cn');

if(isset($_COOKIE[SESSID]))
    session_id($_COOKIE[SESSID]);
session_start();

require CY.'Loader'.DIR_S.'Loader.php';
Loader::loadClass('Cy\Mvc\EventsManager');
EventsManager::run();
?>
