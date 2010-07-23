<?php

/**
 * The proper order to test the response is as follows:
 * 
 * 1) Test for an exception and render the error document.
 * 2) Test for a redirect and render the redirect.
 * 3) Test for a view and render the view
 * 4) Throw an exception and display an error page that the response was malformed.
 */

interface ResponseI
{
	public function setHeader($header, $data);
	
	public function setView($view);	
	
	public function setData($key, $value);
		
	public function setException(Exception $e);
		
	public function setRedirect($redirect);
	
	public function getData();
	
	public function getException();
	
	public function getHeaders();
	
	public function getRedirect();
	
	public function getView();
	
	public function hasException();
	
}