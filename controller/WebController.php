<?php

class WebController extends BaseController
{
	public function execute()
	{
		$formatter = $this->getResponseFormatter();
		
		$response = parent::execute();
		
		$formatter->formatResponse($response);
		
	}
}