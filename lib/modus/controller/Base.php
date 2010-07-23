<?php

abstract class Controller_Base implements Controller_Interface
{
	protected $request;
	protected $response;
	protected $module;
	protected $action;
	protected $formatter;
	
	public function __construct(Request_Interface $request)
	{
		$this->request = $request;
	}
	
	public function authenticate()
	{
		// We've moved the logic for authentication up to the action, since it
		// knows about the session. The action will return true if you can
		// execute the action, or a value if you cannot.
		$result = $this->action->isAuthorized();
		if($result === true) // Force check true; could be a status code.
		{
			return true;
		}
			
		switch($result)
		{
			case 401:
				$e = new Exception_Action('Unauthenticated access attempt.');
				$e->setUserMessage('This action requires authentication. You must log in.');
				throw $e;
				break;
				
			case 403:
				$e = new Exception_Action('The role this user has does not permit this action to be performed.');
				$e->setUserMessage('You do not have the proper permissions to conduct this action.');
				throw $e;
				break;
		}
	}
	
	public function initialize()
	{
		$module = $this->module;
		$action = $this->action;
		if(!$module && !$action)
		{
			throw new Exception_Modus('cannot find module and action!', 404);
		}
		
		if(!$module || !$action)
		{
			throw new ModusException('malformed request');
		}
		
		$clazz = ucfirst($module) . '_' . ucfirst($action);
		
		if(!class_exists($clazz, true))
		{
			$e = new ModusException('Attempted to load class ' . $clazz . ' but it could not be found.');
			$e->setUserMessage('The action you requested has not been implemented yet.');
			throw $e;
		}
		
		$this->action = new $clazz();
		$this->action->setRequest($this->request);
		$this->action->setSession(ModusSession::getSession());
		$this->action->setResponseObject($this->response);
		$this->action->setSession(ModusSession::getSession());
		
	}
	
	public function execute()
	{
		
		$this->module = $this->getModule();
		$this->action = $this->getAction();
		
		try {
            $this->initialize();
			
			$this->authenticate();
			
		    $result = $this->action->perform();
		    
		    if(!($result instanceof ResponseI))
		    {
		    	$result = $this->response;
		    }
		} 
		catch (PropelException $e)
		{
			$ex = new Exception_Modus('There was a database error:' . $e);
			$ex->setUserMessage('There was an unknown database error that prevented your request from being successful.');
			$this->response->setException($ex);
			$result = $this->response;
		}
		catch (Exception_Action $e)
		{
			$this->response->setException($e);
			$result = $this->response;
		}
		catch (Exception $e)
		{			
			if($e->getCode() != 404)
			{
				$this->response->setException($e);
			}
			
			$result = $this->response;
		}
		
		return $result;
		
	}
	
	public function getModule()
	{
		return $this->request->getModule();
	}
	
	public function getAction()
	{
		return $this->request->getAction();

	}
	
	public function setResponseFormatter(Formatter_Interface $rformat)
	{
		$this->formatter = $rformat;
	}
	
	public function getResponseFormatter()
	{
		if($this->formatter instanceof Formatter_Interface)
		{
			return $this->formatter;
		}
		return null; // To be explicit.
	}
	
	public function setResponse(Response_Interface $response)
	{
		$this->response = $response;
	}
	
	public function getResponse()
	{
		if($this->response instanceof Response_Interface)
		{
			return $response;
		}
		return null; // to be explicit.
	}
	
	protected function logErrors($error)
	{
		// $this->log->logError($error);
	}
}