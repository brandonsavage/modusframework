<?php

class ModusSession
{
	
	protected $sessionData = array();
	
	protected static $session;
	
	protected $protectedArray = array('role' => true, 'userObject' => true);
		
	protected function __construct()
	{
		session_start();
		if(isset($_SESSION['ModusData']))
		{
			$this->sessionData = unserialize($_SESSION['ModusData']);
		}
		
		if(!$this->get('validated'))
		{
			session_regenerate_id();
			$this->set('validated', true);
		}
	}
	
	public static function getSession()
	{
		if(is_object(self::$session) && (self::$session instanceof ModusSession))
		{
			$session = self::$session;
		}
		else
		{
			$session = new ModusSession();
			self::$session = $session;
		}
		
		return $session;
	}
	
	public function get($key)
	{
		if(isset($this->sessionData[$key]))
		{
			return $this->sessionData[$key];
		}
		else
		{
			return null; // just to be explicit
		}
	}
	
	public function set($key, $value)
	{
		if(isset($this->protectedArray[$key]))
		{
			throw new ModusException('Unable to set session value; that value is a protected system value');
		}
		
		$this->setOverride($key, $value);
	}
	
	protected function setOverride($key, $value)
	{
		$this->sessionData[$key] = $value;
	}
	
	public function setArray($key, $value)
	{
		$array = (array)$this->get($key);
		$array[] = $value;
		$this->set($key, $array);
	}
	
	public function setUser($user)
	{
		$this->setOverride('userObject', $user);
	}
	
	public function getUser()
	{
		return $this->get('userObject');
	}
	
	public static function getFeedback()
	{
		$session = self::getSession();
		$feedback = $session->get('feedback');
		$errors = $session->get('errors');
		
		$session->remove('feedback')->remove('errors');
		
		return(array($feedback, $errors));
	}
	
	public function remove($key)
	{
		if(isset($this->sessionData[$key])) {
			unset($this->sessionData[$key]);
		}
		
		return $this;
	}
	
	public function setRole($role)
	{
		$this->setOverride('role', (int)$role);
	}
	
	public function getRole()
	{
		$role = $this->get('role');
		if(!$role)
		{
			$role = 1;
		}
		
		return $role;
	}
	
	public static function isLoggedIn()
	{
		$session = self::getSession();
		return (bool)$session->get('loggedin');
	}
	
	public static function logOut()
	{
		return ModusSession::getSession()->destroySession();
	}
	
	public function destroySession()
	{
		session_destroy();
		return true;
	}
	
	public static function addFeedback($message)
	{
		$session = self::getSession();
		$session->setArray('feedback', $message);
		return $session;
	}
	
	public static function addError($message)
	{
		$session = self::getSession();
		$session->setArray('errors', $message);
		return $session;	}
	
	public function __destruct()
	{
		self::$session = null;
		$sessionData = serialize($this->sessionData);
		$_SESSION['ModusData'] = $sessionData;
	}
}