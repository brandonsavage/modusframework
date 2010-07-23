<?php

class GenericResponse implements ResponseI
{
	protected $headers = array();
	protected $view;
	protected $exception;
	protected $redirect;
	protected $data = array();
	protected $defaultView;
	
	final public function setHeader($header, $data)
	{
		if($header == 'Location')
		{
			$this->setRedirect($data);
			return;
		}
		
		$this->headers[] = array('header' => $header, 'data' => $data);
	}
	
	public function setView($view)
	{
		$this->view = $view;
	}	
	
	public function setData($key, $value)
	{
		$this->data[$key] = $value;
	}
		
	public function setException(Exception $e)
	{
		$this->exception = $e;
	}
		
	public function setRedirect($redirect)
	{
		$this->redirect = $redirect;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	public function getException()
	{
		return $this->exception;
	}
	
	public function getHeaders()
	{
		return $this->headers;
	}
	
	public function getRedirect()
	{
		return $this->redirect;
	}
	
	public function getView()
	{
		return $this->view;
	}
	
	public function hasException()
	{
		if($this->exception instanceof Exception)
		{
			return true;
		}
		
		return false;
	}
	
	public function setDefaultView($view)
	{
		$this->defaultView = $view;
	}
	
	public function getDefaultView()
	{
		return $this->defaultView;
	}
}