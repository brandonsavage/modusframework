<?php

class ModusException extends Exception
{
	
	protected $userMessage;
	
	public function __construct($message, $code = 0)
	{
		parent::__construct($message, $code);
	}
	
	public function setUserMessage($message)
	{
		$this->userMessage = $message;
	}
	
	public function getUserMessage()
	{
		if(empty($this->userMessage)) {
			return 'The details are not available.';
		}
		
		return $this->userMessage;
	}
}