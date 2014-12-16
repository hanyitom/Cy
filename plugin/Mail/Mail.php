<?php
/**
 * 简单的Scoket Mail类
 * @author Toby
 */
class Mail
{
	private $from;
	private $to;
	private $copy;
	private $contents;
	private $port;
	private $isHTML;
	private $socket;
	
	public function __construct()
	{
		$this -> from 		= array();
		$this -> to			= array();
		$this -> copy		= array();
		$this -> content	= '';
		$this -> port		= 25;
	}
	
	/**
	 * This function need from info like username,password and mail address
	 * @param array $from
	 */
	public function setFrom($from)
	{
		$this -> from = $from;
		return $this;
	}
	
	/**
	 * Input is the mail address list that you want to send
	 * @param array $to
	 */
	public function setTo($to)
	{
		if ( is_array($to) )
		$this -> to = $to;
		return $this;
	}
	
	/**
	 * Input is the copy list that you want to
	 * @param array $copy
	 */
	public function setCopy($copy)
	{
		$this -> copy($copy);
		return $this;
	}
	
	/**
	 * Input is you Mail contents
	 * @param array $content
	 */
	public function setContent($contents,$isHTML = false)
	{
		$this -> contents = base64_encode($contents);
		$this -> isHTML = $isHTML;
		return $this;
	}
	
	public function setPort($port)
	{
		$this -> port = $port;
		return $this;
	}
	
	private function comication($command)
	{
		socket_write($this -> socket, "$command\r\n");
		return socket_read($this -> socket, 1024);
	}
	
	public function send()
	{
		$host = explode('@',$this -> from);
		$host = 'smtp.'.$host[1];
		if ( !( $this -> socket	= socket_create(AF_INET,SOCK_STREAM,SOL_TCP ) ) )
			return false;
		if ( !socket_connect($this -> socket,$host,$this -> port) )
			return false;
		if ( !strstr(socket_read($this -> socket,1024 ),220) )
			return false;
		if ( !strstr($this -> comication("HELO $host"), 250) )
			return false;
		if ( !strstr($this -> comication("AUTH login"), 334) )
			return false;
		if ( !strstr($this -> comication(base64_encode($this -> from['username'])), 334) )
			return false;
		if ( !strstr($this -> comication(base64_encode($this -> from['password'])), 235) )
			return false;
		if ( !strstr($this -> comication("MAIL From:<".$this -> from['address'].">"), 250) )
			return false;
		if ( !strstr($this -> comication("HELO $host"), 250) )
			return false;
	}
}
