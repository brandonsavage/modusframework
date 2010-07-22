<?php
/**
 * This is a database logger class.
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
 * This class is written to write log events to the database. It is a concrete
 * implementation of the Log2 class.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_Database extends Log2
{
    /**
     * Establishes a PDO connection for the object to utilize.
     * 
     * @param string|PDO $pdoConn  The PDO DSN or a PDO object.
     * @param string     $username The username for connecting to the datbase (if required)
     * @param string     $password The password for connecting to the database (if required)
     * @param array      $options  The driver options for connecting to the database.
     * 
     * @return void
     */
    public function __construct($pdoConn, $username = null, $password = null, array $options = array())
    {
        if (is_object($pdoConn)) {
            if ($pdoConn instanceof PDO) {
                $this->open($pdoConn);
                return;
            }
        }
        
        // Let's test to see that a string was passed if it's not an object.
        if (!is_string($pdoConn)) {
            throw new Log2_Database_Exception('$pdoConn must either be a DSN or a PDO object; non-string given');
        }
        
        try {
            $conn = new PDO($pdoConn, $username, $password, $options);
            // We shouldn't have to test that a PDO object was returned, but due to
            // Bug #49320 we may not get back an object.
            if (!is_object($conn)) {
                throw new PDOException('Connection not established properly for unknown reasons');
            }
           
            $this->open($conn);
           
        } catch (PDOException $e) {
            throw new Log2_Database_Exception('Unable to establish database connection: ' . $e->getMessage());
        }
    } 

    /**
     * Sets the PDO connection as the object handle for later use.
     * 
     * @param PDO $pdoConn The PDO object with a connection.
     * 
     * @return void
     */
    protected function open($pdoConn = false)
    {
        if (!is_object($pdoConn) || !($pdoConn instanceof PDO)) {
            throw new Log2_Database_Exception('Non-object or non-PDO object supplied to Log2_Database::open()');
        }
        
        $this->logHandle = $pdoConn;
    }

    /**
     * Closes the PDO connection.
     * 
     * @return void
     */
    protected  function close()
    {
        $this->logHandle = false;
    }

    /**
     * Logs a message to the database.
     * 
     * @param string|Exception $message The message to be logged.
     * @param integer          $level   The level of the log event.
     * 
     * @return bool
     */
    public function log($message, $level = Log2::NOTICE)
    {
        if (is_object($message) && ($message instanceof Exception)) {
            $message = $this->extractMessage($message);
        }
        
        if (!isset($this->priorityText[$level])) {
            throw new Log2_Mail_Exception('Severity level provided is not defined in Log2');
        }

        $logged = false;
        
        $time = new DateTime("now");
        $timeStr = $time->format('r');
        
        $conn = $this->logHandle;
        
        try {
            $conn->beginTransaction();
            $stmt = $conn->prepare('INSERT INTO log2_database (time, level, message) VALUES (?, ?, ?)');
            
            $stmtArr[] = $timeStr;
            $stmtArr[] = $level;
            $stmtArr[] = $message;
            
            $logged = $stmt->execute($stmtArr);
            $this->notifyObservers($message, $level, $time);
            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            throw new Log2_Database_Exception('Unable to write to database: ' . $e->getMessage());
        }
        
        if ($logged) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * A destructor function.
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
 
}