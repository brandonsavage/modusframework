<?php
/**
 * The base abstract Log class.
 * 
 * This base class is meant to be extended by other classes to
 * implement specific functionality.
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

require_once 'Log2/Exception.php';

/**
 * The logger abstract class.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
abstract class Log2
{

    const EMERG   = 0;     /* System is unusable */
    const ALERT   = 1;     /* Immediate action required */
    const CRIT    = 2;     /* Critical conditions */
    const ERR     = 3;     /* Error conditions */
    const WARNING = 4;     /* Warning conditions */
    const NOTICE  = 5;     /* Normal but significant */
    const INFO    = 6;     /* Informational */
    const DEBUG   = 7;     /* Debug-level messages */

    /**
     * This will set the maximum level for logging. Can be modified.
     * @var int
     */
    protected  $maxLogging = 7;
    
    /**
     * Array that corresponds to the class constants, and creates a string
     * that can be used in the logging process.
     * @var array
     */
    protected $priorityText =   array('emergency',
                                       'alert',
                                        'critical',
                                        'error',
                                        'warning',
                                        'notice',
                                        'info',
                                        'debug',);
    
    /**
     * The handle used to write to the log file. May be a file pointer, a database
     * connection, etc.
     * @var resource
     */
    protected $logHandle;
    
    /**
     * Represents the current message being passed, or the last message
     * that was passed.
     * @var string
     */
    protected $message;
    
    /**
     * Variable containing the observers waiting for messages from this
     * class.
     * @var array
     */
    protected $observers = array();
    
    /**
     * An abstract method for the logging of a message to the appropriate
     * log.
     * 
     * @param mixed   $message The message to be logged.
     * @param integer $level   The level of notice.
     * 
     * @return void
     */
    public abstract function log($message, $level = Log2::NOTICE);
    
    /**
     * Registers an observer to receive messages from this logging class.
     * 
     * @param Log2_Observer $observer The observer object being passed.
     * 
     * @return void
     */
    public function registerObserver(Log2_ObserverI $observer)
    {
        $this->observers[] = $observer;
    }
    
    /**
     * Sets the maximum log level for the logger class.
     * 
     * @param int $logLevel The level of which to log. Use a Log2 constant.
     * 
     * @return bool
     */
    public function setLogLevel($logLevel)
    {
        $logLevel = (int) $logLevel;
        $this->maxLogging = $logLevel;
        return true;
    }
    
    /**
     * Abstract method for the opening of a log to write to. May be extended
     * to use a database or a file.
     * 
     * @return bool
     */
    protected abstract function open();
    
    /**
     * Abstract method for the closing of the log being written to. May be
     * extended for a database or a file.
     * 
     * @return bool
     */
    protected abstract function close();
    
    /**
     * If an exception is passed to the log method, the log method should
     * request that the exception be processed and the message returned for
     * logging.
     * 
     * This method may be extended to work with various types of exceptions
     * that may contain advanced tools (like exception wrapping).
     * 
     * @param Exception $exception The exception to log.
     * 
     * @return string
     */
    protected function extractMessage(Exception $exception)
    {
        return $exception->getMessage();
    }
    
    /**
     * Notifies observer objects that a message has been logged.
     * 
     * @param string   $message The message being logged.
     * @param int      $level   The level of item being logged.
     * @param DateTime $time    The DateTime object representing the time of the 
     *                                  logged event.
     * 
     * @return bool
     */
    protected function notifyObservers($message, $level, DateTime $time)
    {
        foreach ($this->observers as $observer) {
            $observer->passMessage($message, $level, $time);
        }
    }
    
}