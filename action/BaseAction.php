<?php

require 'ActionI.php';

abstract class BaseAction implements ActionI
{
	protected $request;
	protected $response;
	protected $session;
	
	protected $requiredRole = 1;
	
    public function setRequest(RequestI $request)
    {
    	$this->request = $request;
    }

    public function getRequest()
    {
    	return $this->request;
    }

    public function setSession(ModusSession $session)
    {
    	$this->session = $session;
    }
    
    public function getSession()
    {
    	return $this->session;
    }
    
    public function setResponseObject(ResponseI $response)
    {
    	$this->response = $response;
    }
    
    public function getResponseObject()
    {
    	return $this->response;
    }
    
    public function requiresAuth()
    {
    	return true;
    }
    
    public function isAuthorized()
    {
    	if(!$this->requiresAuth())
    	{
    		return true;
    	}
    	
    	if($this->requiresAuth() && !ModusSession::isLoggedIn())
    	{
    		return 401;
    	}
    	
    	if($this->session->getRole() < $this->getRequiredRole())
    	{
    		return 403;
    	}
    }
    
    public function getRequiredRole()
    {
    	return $this->requiredRole;
    }
    
    public function perform()
    {
    	$this->initialize();
    	return $this->execute();
    }
    
    protected abstract function execute();
    
    protected abstract function initialize();
}