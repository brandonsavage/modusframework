<?php

require_once 'PHPUnit/Framework.php';
require_once 'Log2.php';
require_once 'Log2/Database.php';
require_once 'Log2/Exception.php';

class Log2_DatabaseTestSuite extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        return new Log2_DatabaseTestSuite('Log2_DatabaseTest');
    }
 
    protected function setUp()
    {
        $conn = new PDO('sqlite2::memory:');
        $this->sharedFixture = $conn;
        $conn->query('CREATE TABLE log2_database (time varchar(30) NOT NULL, level int(2) NOT NULL, message varchar(255) NOT NULL)');
    }
 
    protected function tearDown()
    {
        unset($this->sharedFixture);
    }
}

class Log2_DatabaseTest extends PHPUnit_Framework_TestCase
{
   public function testPDOConnection()
   {
        $object = new Log2_Database($this->sharedFixture);
        $this->assertType('object', $object);
        $this->assertTrue($object instanceof Log2_Database);
        
        $this->setExpectedException('Log2_Database_Exception');
        $object = new Log2_Database('invalidDSN');
   }
   
   public function testLogging()
   {
        $conn = $this->sharedFixture;
        $object = new Log2_Database($this->sharedFixture);
        $return = $object->log('Test message', Log2::EMERG);
        
        $this->assertTrue($return);
        
        $stmt = $conn->query('SELECT * FROM log2_database LIMIT 1');
        $array = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertArrayHasKey('time', $array);
        $this->assertArrayHasKey('level', $array);
        $this->assertArrayHasKey('message', $array);
   }
}

?>