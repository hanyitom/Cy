<?php
namespace Cy\Mvc;

use Cy\Mvc\EventsManager;

class Request
{
	private $_REQUEST_CLASS	    =	'index';
	private $_REQUEST_ACTION	=	'index';
    private $_REQUEST_PARAM	    =	array();
    private $_LANGUAGES         =   array('cn','en','jp');
    private $_LANGUAGE          =   LANGUAGE;

    private function __construct($isIndex)
    {
		$this->parseRequest($isIndex);
	}

    public static function getInstance($isIndex = false)
    {
        return new self($isIndex);
    }

	public function parseRequest($isIndex)
	{
		$uri = substr( $_SERVER['REQUEST_URI'], 1);
		$tmp = explode('/', $uri);
		if ( $tmp1 = array_shift($tmp) )
		{
            if (in_array($tmp1, $this->_LANGUAGES))
            {
                $this->_LANGUAGE = $tmp1;
                $tmp1 = array_shift($tmp);
            }
            $this->_REQUEST_CLASS = $tmp1;
           	if( !$isIndex && ($tmp1 = array_shift($tmp)) )
			    $this->_REQUEST_ACTION = $tmp1;
		   	$this->_REQUEST_PARAMS = $tmp;
		}
		unset($tmp,$tmp1);
	}

	public function getClass()
	{
		return $this->_REQUEST_CLASS;
	}

	public function getParams()
	{
		return $this->_REQUEST_PARAMS;
	}

    public function getLanguage()
    {
        return $this->_LANGUAGE;
    }

	public function getAction()
	{
		return $this->_REQUEST_ACTION;
	}
}
