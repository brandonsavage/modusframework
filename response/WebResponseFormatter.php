<?php

class WebResponseFormatter implements FormatterI
{
	protected $request;
	
	protected function selectFormatterFile($module, $action = null)
	{
		if($module == 'error' & $action == null)
		{
			return 'error.php';
		}
		
		$path = $module . '/' . $action . '.php';
		
		return $path;
	}
	
	public function formatResponse(ResponseI $response)
	{
		$this->sendHeaders($response->getHeaders());

		if($response->hasException())
		{
			$data = $this->handleException($response->getException());
			$this->renderView($data, 'error.php');
		}
		elseif($response->getRedirect())
		{
			$this->renderRedirect($response->getRedirect());
		}
		elseif($response->getView())
		{
			$this->renderView($response->getData(), $response->getView());
		}
		elseif($response->getDefaultView())
		{
			$this->renderRedirect($response->getDefaultView());
		}
		else
		{
			$data = 'The response was malformed and could not be rendered.';
			$this->renderView($data, 'error.php');
		}
	}
	
	protected function renderRedirect($redirect)
	{
		header("Location: " . $redirect);
		return;
	}
	
	protected function sendHeaders($headers)
	{
		foreach($headers as $header)
		{
			header($header['header'] . ': ' . $header['data']);
		}
	}
	
	protected function renderView($data, $view)
	{
		$vars = $data;
		require 'views/' . $view;
	}
	
	protected function handleException(Exception $e)
	{		
		if(!method_exists($e, 'getUserMessage'))
			{
				$data = 'There was an unknown error processing your request.';
			}
			else
			{
				$data = $e->getUserMessage();
			}
			
			return $data;
	}
    
    public function renderDefault()
    {
    	require 'views/default.php';
    }
}