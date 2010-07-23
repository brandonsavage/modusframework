<?php
/**
 * File containing the exceptions that exist for Log2.
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

require_once 'PEAR/Exception.php';

/**
 * The Log2 exception.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_Exception extends PEAR_Exception
{
}

/**
 * The Log2_File exception.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_File_Exception extends Log2_Exception
{
}

/**
 * The Log2_Database exception.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_Database_Exception extends Log2_Exception
{
}

/**
 * The Log2_Mail exception.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
class Log2_Mail_Exception extends Log2_Exception
{
}