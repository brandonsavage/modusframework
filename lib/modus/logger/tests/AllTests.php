<?php

require_once 'Log2_FileTest.php';
require_once 'Log2_MailTest.php';
require_once 'Log2_DatabaseTest.php';
require_once 'PHPUnit/Framework.php';

require_once('../Log2.php');

class Package_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Package');
 
        $suite->addTestSuite('Log2_FileTestSuite');
        $suite->addTestSuite('Log2_MailTest');
        $suite->addTestSuite('Log2_DatabaseTestSuite');
       
        // ...
 
        return $suite;
    }
}