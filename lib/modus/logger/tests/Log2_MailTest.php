<?php

require_once 'PHPUnit/Framework.php';
require_once 'Mail.php';

require_once 'Mail/mail.php';
require_once 'Log2.php';
require_once 'Log2/Mail.php';
require_once 'Log2/MailerI.php';
require_once 'Log2/Exception.php';

// A mock object to test with.
class MailTest extends Mail_mail
{
	public $returnVal = true;
	
	public function mail($recipients, $headers, $body)
	{
	   if (!isset($headers['Subject'])) {
            return 'incorrect parameters';
	   }
	   
	   return $this->returnVal;
	}

   public function setReturnVal($bool = false)
    {
        $this->returnVal = $bool;
    }

}

class MailerTest implements Log2_MailerI
{
    public $returnVal = true;

	public function sendLogMail(array $recipients, $subject, array $headers, $body)
	{
	   return $this->returnVal;
	}
	
	public function setReturnVal($bool = false)
	{
		$this->returnVal = $bool;
	}
}

class Log2_MailTest extends PHPUnit_Framework_TestCase
{
	protected $mailObj;
	protected $mailerObj;
	protected $logObj;
	
	protected function setUp()
	{
		$this->mailObj = new MailTest();
		$this->mailerObj = new MailerTest();
		$this->logObj = new Log2_Mail();
	}
	
	protected function tearDown()
	{
		$this->mailObj = $this->logObj = false;
	}
	
	// Let's do a sanity check and make sure our objects are appropriate.
	public function testSanity()
	{
		$this->assertTrue(($this->mailObj instanceof Mail_mail));
		$this->assertTrue(($this->logObj instanceof Log2_Mail));
		$this->assertTrue(($this->mailerObj instanceof Log2_MailerI));
	}
	
	public function testSetMailer()
	{
		$object = $this->logObj;
		$this->assertFalse($object->setMailer(new stdClass()));
		
		$this->assertTrue($object->setMailer($this->mailObj));
	}
	
	public function testSendMail()
	{
		$object = $this->logObj;
		
		$object->setMailer($this->mailerObj);
		$object->setRecipient('test@example.com');
		$object->setHeader('From: Test Test <test@example.com>');
        $result = $object->log('test message', Log2::EMERG);
        $this->assertTrue($result);
        
        $object->setMailer(new stdClass());
        $this->setExpectedException('Log2_Mail_Exception');
        $object->log('unable to log', Log2::EMERG);
				
	}
	
	public function testSendMailFail()
	{
		$object = $this->logObj;
		$object->setMailer($this->mailerObj);
		$this->mailerObj->setReturnVal();
        $object->setRecipient('test@example.com');
        $object->setHeader('From: Test Test <test@example.com>');
        $result = $object->log('test', Log2::EMERG);
        
        $this->assertFalse($result);
	}
	
	public function testInavlidLogLevel()
	{
		$this->setExpectedException('Log2_Mail_Exception');
		$this->logObj->log('text', 95); // Clearly invalid; will get an exception.
	}
}