<?php
/**
 * This is a mail logger class.
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
 * This class is written to email log events to the system administrator. 
 * It is an implementation of the Log class.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_Mail extends Log2
{
  
    protected $recipients;
    protected $preamble;
    protected $subject = '[Log2 Mail] %s';
    protected $headers;
     
    /**
     * Create a mail object.
     * 
     * This function will accept either a Mail object, or an object
     * that implements the Log_MailerI interface. This interface can
     * be used by a developer to write their own mailing script.
     * 
     * @param object $mail An object to send mail.
     * 
     * @return bool
     */
    protected  function open($mail = false)
    {
        if (($mail instanceof Mail)
            || ($mail instanceof Log2_MailerI)
        ) {    
            $this->logHandle = $mail;
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Destroy any existing Mail object.
     * 
     * @return bool
     */
    protected  function close()
    {
        $this->logHandle = false;
        return true;
    }
    
    /**
     * Send an email on a log event.
     * 
     * @param string|Exception $message The message being logged.
     * @param integer          $level   The level of the message being logged.
     * 
     * @return bool
     */
    public  function log($message, $level = Log2::NOTICE)
    {                
        if (is_object($message) && ($message instanceof Exception)) {
            $message = $this->extractMessage($message);
        }
        
        if (!isset($this->priorityText[$level])) {
            throw new Log2_Mail_Exception('Severity level provided is not defined in Log2');
        }
        
        $time = new DateTime("now");
        $timeStr = $time->format('r');
        
        $sendMsg = false;
        
        if (!empty($this->preamble)) {
            $sendMsg .= $this->preamble . PHP_EOL . PHP_EOL;
        }
        
        $sendMsg .= $timeStr . ": ";
        $sendMsg .= strtoupper($this->priorityText[$level]) . ' ';
        $sendMsg .= $message;
        
        if ($this->logHandle instanceof Mail) {
            // Because of the kludgy way Mail accepts arguments, we have to put the subject
            // in with the headers.
            $headers = $this->headers;
            $headers['Subject'] = $this->subject;
            
            $sent = $this->logHandle->send($this->recipients, $headers, $sendMsg);
        } else if ($this->logHandle instanceof Log2_MailerI) {
            $sent = $this->logHandle->sendLogMail($this->recipients, $this->subject, $this->headers, $sendMsg);
        } else {
            throw new Log2_Mail_Exception('Incorrect mailer object used! Unable to write log');
        }
        
        $this->notifyObservers($message, $level, $time);
                
        if ($sent) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Set the recipients of the email log notification.
     * 
     * @param string|array $recipient The recipient(s) of the email.
     * 
     * @return bool
     */
    public function setRecipient($recipient)
    {
        if (is_array($recipient)) {
            foreach ($recipient as $individual) {
                if (filter_var($individual, FILTER_VALIDATE_EMAIL)) {
                    $this->recipients[] = $individual;
                } 
            }
            return true;
        }
        
        if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            $this->recipients[] = $recipient;
            return true;
        }
        
        return false;
    }
    
    /**
     * Sets the preamble that is prepended to the email log message.
     * 
     * @param string $preamble The preamble to be prepended to the message.
     * 
     * @return bool
     */
    public function setPreamble($preamble)
    {
        if (!is_string($preamble)) {
            return false;
        }
        
        $this->preamble = $preamble;
        return true;
    }
    
    /**
     * Sets the subject for the log email message.
     * 
     * @param string $subject The subject.
     * 
     * @return bool
     */
    public function setSubject($subject)
    {
        if (!is_string($subject)) {
            return false;
        }
        
        $this->subject = $subject;
        return true;
    }
    
    /**
     * Set the header(s) on the email.
     * 
     * @param string|array $header The headers to be set for the email.
     * 
     * @return bool
     */
    public function setHeader($header)
    {
        if (is_array($header)) {
            foreach ($header as $oneHeader) {
                $this->headers[] = $oneHeader;
            }
            return true;
        }
        
        $this->headers[] = $header;        
        return true;
    }
 
    /**
     * Allows the injection of the mailer object. This makes testing easier.
     * 
     * @param Mail_mail|Log2_MailerI $object The mailer object to be used.
     * 
     * @return bool
     */
    public function setMailer($object)
    {
        $this->close();
        return $this->open($object);
    }
}