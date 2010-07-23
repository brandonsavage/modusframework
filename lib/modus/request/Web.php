<?php

class Request_Web extends Request
{
	protected $addlData;
	
	protected function populateData(array $array=array())
	{
		if(isset($_GET['module'])) {
		$this->module = $_GET['module'];
		}
		
		if(isset($_GET['action'])) {
		  $this->action = $_GET['action'];
		}
		
		if(isset($_GET['record'])) {
		  $this->request = $_GET['record'];
		}
	    
		if(!empty($_GET['addlData'])) {
			$result = array();
			
			$additionalData = urldecode($_GET['addlData']);
			$array = explode(',', $additionalData);
			foreach($array as $item)
			{
				$kvpair = explode('|', $item);
				$result[$kvpair[0]] = $kvpair[1];
			}
			
			$this->addlData = $result;
		}
		
		if(isset($_POST['module'])) {
        $this->module = $_GET['module'];
        }
        
        if(isset($_POST['action'])) {
          $this->action = $_GET['action'];
        }
        
        if(isset($_POST['record'])) {
          $this->request = $_GET['record'];
        }		
		parent::populateData($_POST);
	}
	
	public function getAdditionalData($key)
	{
		if(isset($this->addlData[$key])) {
			return $this->addlData[$key];
		}
	}
}