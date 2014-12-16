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
	private $subject;
	
	public function __construct()
	{
		$this -> from 		= array();
		$this -> to			= array();
		$this -> copy		= array();
		$this -> content	= '';
		$this -> port		= 25;
		$this -> subject	= '';
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
		$this -> contents = $contents;
		$this -> isHTML = $isHTML;
		return $this;
	}
	
	public function setSubject($subject){
		$this -> subject = $subject;
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
	
	private function data(){
		if ( !strstr($this -> comication("DATA"), '354') )
			return false;
		socket_write($this -> socket, "Content-type:text/html;charset=utf-8\r\n");
		if( $this -> subject != '' )
			socket_write($this -> socket, "Subject: ". $this -> subject. "\r\n");
		socket_write($this -> socket, "\r\n");
		socket_write($this -> socket, $this -> contents. "\r\n");
		socket_write($this -> socket, ".\r\n");
		return socket_read($this -> socket, 1024);
	}
	
	public function send()
	{
		$host = explode('@',$this -> from['address']);
		$host = 'smtp.'.$host[1];
		if ( !( $this -> socket	= socket_create(AF_INET,SOCK_STREAM,SOL_TCP ) ) )
			return false;
		if ( !socket_connect($this -> socket,$host,$this -> port) )
			return false;
		if ( !strstr(socket_read($this -> socket,1024 ),'220') )
			return false;
		if ( !strstr($this -> comication("HELO $host"), '250') )
			return false;
		if ( !strstr($this -> comication("AUTH login"), '334') )
			return false;
		if ( !strstr($this -> comication(base64_encode($this -> from['username'])), '334') )
			return false;
		if ( !strstr($this -> comication(base64_encode($this -> from['passwd'])), '235') )
			return false;
		if ( !strstr($this -> comication("MAIL From:<".$this -> from['address'].">"), '250') )
			return false;
		foreach($this -> to as $to){
			if ( !strstr($this -> comication("RCPT TO: <$to>"), '250') )
				return false;
		}
		if ( !strstr($this -> comication("RCPT TO: <$to>"), '250') )
				return false;
		if ( !strstr($this -> data(), '250') )
			return false;
		if ( !strstr($this -> comication("QUIT"), '221') )
			return false;
		return true;
	}
}
