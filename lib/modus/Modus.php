<?php

/**
 * This abstract class does a number of basic things for Modus. First, it sets the include 
 * path properly, so that the framework can find it's files. Second, it establishes the
 * particulars of the autoloader, so that the files can be autoloaded.
 */
abstract class Modus
{
	protected static $files;
	
	public static $hooks = array();
	
	public static function oldAutoload($className)
	{
	   if(empty(self::$files))
	   {
	   	  //$frameworkLib = include 'lib/manifest.php';
	   	  $applicationLib = include 'webapp/manifest.php';
	   	  
	   	  //$files = array_merge($frameworkLib, $applicationLib);
	   	  
	   	  self::$files = $applicationLib;
	   }
	   else
	   {
	   	   $files = self::$files;
	   }
	   
	   if(isset($files[$className]))
	   {
		require_once $files[$className];
		}
	}

  public static function newAutoload($className)
  {
    //var_dump($className); die;
    $fileParts = explode('_', $className);
    if(count($fileParts) > 1) {
      $fileName = array_pop($fileParts);
      $path = null;
      foreach($fileParts as $part) {
        $part = strtolower($part);
        $path .= $part . '/';
      }
    } else {
      $fileName = array_pop($fileParts);
      $path = null;
    }
   // var_dump('modus/' . $path . $fileName . '.php');
    require 'modus/' . $path . $fileName . '.php';
  }
	
    public static function setAutoloader()
    {
    	spl_autoload_register(array('Modus', 'oldAutoload'));
      spl_autoload_register(array('Modus', 'newAutoload'));
    }
	
	public static function setIncludePath($includePath, $modelRuntime, $modelPath)
	{
		set_include_path($includePath . PATH_SEPARATOR . $includePath . '/lib' . PATH_SEPARATOR . $modelRuntime . PATH_SEPARATOR . $modelPath .PATH_SEPARATOR . get_include_path());
		// Added Base Path
		// Added path to Propel files (lib/modus/propel/classes)
		// Added path to model files (webapp)
	
	}
}