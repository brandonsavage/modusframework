<?php
/**
 * This is an interface for Log observers.
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


/**
 * The observer interface. All observers must implement this interface
 * in order to be able to observe Log2.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
interface Log2_ObserverI
{
    /**
     * Method for the passing of a message from the logger to an
     * observer.
     * 
     * @param string   $message The message being sent to the observer.
     * @param int      $level   The level of the item being logged.
     * @param DateTime $time    The DateTime object of the item being logged.
     * 
     * @return void
     */
    public function passMessage($message, $level, DateTime $time);
}