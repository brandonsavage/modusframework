<?php

interface ActionI
{

	public function setRequest(RequestI $request);

	public function getRequest();
	
	public function requiresAuth();
	
	public function getRequiredRole();
	
	public function setSession(ModusSession $session);
	
	public function getSession();
	
	public function setResponseObject(ResponseI $response);
	
	public function getResponseObject();
	
	public function perform();
	
	public function isAuthorized();

}
