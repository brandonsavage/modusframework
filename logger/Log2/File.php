<?php
/**
 * This is a file logger class.
 * 
 * PHP Version 5
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   SVN: $Id:$
 * @link      http://www.brandonsavage.net/
 */

require_once 'Log2.php';

/**
 * This class is written to write log events to a custom system log.
 * It is a concrete implementation of the Log class.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_File extends Log2
{
 
    protected  $filePath;

    /**
     * Function that returns a file pointer for the purpose of writing to the logs.
     * 
     * @throws Log2_File_Exception In the event that the file pointer cannot be established,
     *                             an exception is raised explaining the reason why.
     * 
     * @return resource
     */
    protected  function open()
    {
        // Without a file path there's no way that we have a valid log handle or any chance
        // of getting one. Throw an exception.
        if (empty($this->filePath)) {
            throw new Log2_File_Exception('No file path specified; unable to open file for writing');
        }
        
        // No sense in reopening it if it already exists.
        if (is_resource($this->logHandle) && (get_resource_type($this->logHandle) == 'file')) {
            return $this->logHandle;
        }
        
        $fp = fopen($this->filePath, 'a');
        if (!$fp) {
            throw new Log2_File_Exception('Unable to open file for log writing! Check PHP error log for more details.');
        }
        
        $this->logHandle = $fp;
        
        return $fp;
    }
    
    /**
     * Closes the file pointer established in Log2_File::open().
     * 
     * @return bool
     */
    protected  function close()
    {
        fclose($this->logHandle);
        return true;
    }
    
    /**
     * Logs an error message provided by the application.
     * 
     * @param string|Exception $message The message to log.
     * @param int              $level   The level of the log event.
     * 
     * @throws Log2_File_Exception Throws an exception if the log severity provided is not defined.
     * 
     * @return bool
     */
    public  function log($message, $level = Log2::NOTICE)
    {
        // Open a connection
        $fp = $this->open();
        
        if (is_object($message) && ($message instanceof Exception)) {
            $message = $this->extractMessage($message);
        }
        
        if (!isset($this->priorityText[$level])) {
            throw new Log2_File_Exception('Severity level provided is not defined in Log2');
        }
        
        $time = new DateTime("now");
        $timeStr = $time->format('r');
        
        $writeMsg = $timeStr;
        $writeMsg .= ' [' . $this->priorityText[$level] . '] ';
        $writeMsg .= $message;
        $writeMsg .= PHP_EOL;
        
        $written = fwrite($fp, $writeMsg);
        
        $this->notifyObservers($message, $level, $time);
        
        if ($written) {
            return $writeMsg;
        } else {
            return false;
        }
    }

    /**
     * Based on the condition, it may be desirable to write to multiple different
     * logs. This function allows the setting of an error log path, which will then
     * be opened and written to at the next instance of a log event.
     * 
     * @param string $logFilePath The path to the log file.
     * 
     * @return bool
     */
    public  function setLogFile($logFilePath)
    {
        // Let's unset any existing file pointer.
        if (!empty($this->logHandle)) {
            $this->close();
        }
        
        // Let's set the file path 
        $this->filePath = $logFilePath;
        
        return true;
    }
    
}