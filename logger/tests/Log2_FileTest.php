<?php

require_once 'PHPUnit/Framework.php';
require_once 'Log2.php';
require_once 'Log2/File.php';

class Log2_FileTestSuite extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        return new Log2_FileTestSuite('Log2_FileTest');
    }
 
    protected function setUp()
    {
        fopen('application.log', 'w');
        $this->sharedFixture = new Log2_File();
    }
 
    protected function tearDown()
    {
       unlink('application.log');
    }
}

class Log2_FileTest extends PHPUnit_Framework_TestCase
{
    public function testSetFile()
    {
    	$object = $this->sharedFixture;
    	$val = $object->setLogFile('application.log');
    	$this->assertTrue($val);
    }
    
    public function testWriteFile()
    {
		$object  = $this->sharedFixture;
		$emerg   = $object->log('Emergency entry', Log2::EMERG);
		$alert   = $object->log('Alert entry', Log2::ALERT);
		$crit    = $object->log('Critical entry', Log2::CRIT);
		$err     = $object->log('Error entry', Log2::ERR);
		$warning = $object->log('Warning entry', Log2::WARNING);
		$notice  = $object->log('Notice entry', Log2::NOTICE);
		$info    = $object->log('Info entry', Log2::INFO);
		$debug   = $object->log('Debug entry', Log2::DEBUG);
		
		$this->assertType('string', $emerg);
		
        $this->assertRegExp('$\[emergency\] Emergency entry$', $emerg);
        $this->assertRegExp('$\[alert\] Alert entry$', $alert);
        $this->assertRegExp('$\[critical\] Critical entry$', $crit);
        $this->assertRegExp('$\[error\] Error entry$', $err);
        $this->assertRegExp('$\[warning\] Warning entry$', $warning);
        $this->assertRegExp('$\[notice\] Notice entry$', $notice);
        $this->assertRegExp('$\[info\] Info entry$', $info);               
	    $this->assertRegExp('$\[debug\] Debug entry$', $debug);               
        
}
    
    public function testNotTruncated()
    {
    	$object = new Log2_File();
    	$object->setLogFile('application.log');
        $logs = file('application.log');
        $this->assertEquals(8, count($logs));
    }

}

?>