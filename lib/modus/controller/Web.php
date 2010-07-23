<?php

class Controller_Web extends Controller_Base
{
	public function execute()
	{
		$formatter = $this->getResponseFormatter();
		
		$response = parent::execute();
		
		$formatter->formatResponse($response);
		
	}
}