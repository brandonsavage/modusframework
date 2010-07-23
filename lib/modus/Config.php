<?php

abstract class Config
{
	const CONFIG_ARRAY = 1;
	const CONFIG_INI = 2;
	
	protected static $config = array();
	
	public static function readConfigFile($file, $type = Config::CONFIG_ARRAY)
	{
		switch($type)
		{
			case self::CONFIG_ARRAY:
				self::readFileArray($file);
				break;
			
			case self::CONFIG_INI:
				self::readFileIni($file);
				break;
		}
	}
	
	public static function readConfigArray(array $config)
	{
		self::$config = $config;
	}
	
	public static function get($value)
	{
		$values = explode('/', $value);
		$storedVals = self::$config;
		foreach($values as $value)
		{
			if(!isset($storedVals[$value]))
			{
				var_dump($storedVals);
				var_dump($value);
				trigger_error('The value you requested was not found.', E_USER_WARNING);
				return;
			}
			$storedVals = $storedVals[$value];
		}
	return $storedVals;
	}
	
	protected static function readFileArray($file)
	{
		require $file;
		
		if(!isset($config))
		{
			throw new ConfigException('Unable to properly configure the application.');
		}
		
		self::$config = $config;
			
	}
	
	protected static function readFileIni()
	{
		
	}
}