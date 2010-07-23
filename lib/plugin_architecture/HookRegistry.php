<?php

abstract class HookRegistry
{
	public static function registerHook($hookName, $callback, $priority = 5)
	{
		if(!isset(self::$hooks[$hookName])) {
			self::$hooks[$hookName] = array();		
		}
		
		$hook = self::$hooks[$hookName];
		$hook[$priority][] = $callback;
		ksort($hook);
		self::$hooks[$hookName] = $hook;
		
		return true;
	}
	
	public static function unregisterHook($hookName, $callback)
	{
		if(!isset(self::$hooks[$hookName])) {
			return true;
		}
		
		$hook = self::$hooks[$hookName];
		throw new ModusException('This functionality has not yet been implemented completely');
	}
}