<?php

interface Request_Interface
{
	const STRING = 'string';
	const INT = 'int';
	const BOOL = 'bool';
	const FLOAT = 'float';
	const ARR = 'array';
	const OBJECT = 'object';
		
	public function get($data);
	
	public function set($k, $v);
	
	public function has($v);
	
	public function getAs($data, $type = self::STRING);
	
	public function getRaw();
	
}