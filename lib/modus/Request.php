<?php

class Request implements Request_Interface
{
	protected $processedData = array();
	
	protected $module;
	protected $action;
	protected $request;
	
	protected $rawData = array();
	
	/**
	 * 
	 */
	public function __construct(array $data = array()) {
		$this->populateData($data);
	}

	/**
	 * 
	 */
	public function get($data) {
		if($this->has($data)) {
			return $this->processedData[$data];
		} else {
			return false;
		}
	}

	/**
	 * 
	 */
	public function set($k, $v) {
		$this->processedData[$k] = $v;
	}

	/**
	 * 
	 */
	public function has($v) {
		if(isset($this->processedData[$v])) {
			return true;
		} else {
			return false;
		}
	}

	public function getAs($data, $type = self::STRING) {
		
		if(!$this->has($data)) {
			return false;
		}
		
		$dataValue = $this->get($data);
		return $this->castValue($dataValue, $type);
	}
	
	/**
	 * 
	 */
	public function getRaw($data = false) {
		if(!$data) {
			return $this->rawData;
		}
		
		if(isset($this->rawData[$data])) {
			return $this->rawData[$data];
		} else {
			return false;
		}
	}
	
	/**
	 * This base method creates two arrays that are identical. In decendent
	 * classes this method should be overridden to do more stringent filtering
	 * based on the application requirements.
	 * 
	 * For example, a developer might opt to add a FormInputRequest class that
	 * automatically strips HTML out of the processed data, or an XMLRequest that
	 * automatically turns XML into objects based on certain conditions.
	 * 
	 * @param array $array
	 * @return unknown_type
	 */
	protected function populateData(array $data=array()) {
		
		// First, let's assign the raw data to the raw data value.
		$this->rawData = $data;
		
		// Now let's process this data.
		foreach($data as $k => $v) {
			$this->set($k, $v);
		}
	}
	
	/**
	 * 
	 * @param $dataValue
	 * @param $type
	 * @return mixed
	 */
	protected function castValue($data, $type) {
		switch ($type) {
			case self::STRING:
				return (string) $data;
				break;
				
			case self::INT:
				return (int) $data;
				break;
			
			case self::BOOL:
				return (bool) $data;
				break;
				
			case self::FLOAT:
				return (float) $data;
				break;
			
			// In the next two cases, we don't want to transform the data
			// if we can avoid it, so we'll test to see if it's already that
			// before we cast it.
			case self::ARR:
				if(is_array($data)) {
					return $data;
				}
				return (array) $data;
				break;
			
			case self::OBJECT:
				if(is_object($data)) {
					return $data;
				}
				return (object) $data;
				break;
			
			default:
				throw new RequestException('Invalid type defined');
				break;
		}
	}
	
	public function getModule()
	{
		return $this->module;
	}
	
	public function getAction()
	{
		return $this->action;
	}
	
	public function getRecord()
	{
		return $this->request;
	}
}