<?php
/**
 * This is an interface for creating a Log2-compatible mailer.
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
 * This interface is designed to create a Log2-compatible mailer.
 * 
 * @category  Logging
 * @package   Log2
 * @author    Brandon Savage <brandon@brandonsavage.net>
 * @copyright 2009 Brandon Savage
 * @license   http://www.opensource.org/licenses/bsd-license.php BSD License
 * @version   Release: @package_version@
 * @link      http://www.brandonsavage.net/
 */
interface Log2_MailerI
{
    /**
     * This method is designed to facilitate the implementation of a logger-specific
     * emailer.
     * 
     * The function's odd name is designed to prevent namespace collisions.
     * 
     * Since PHP permits the implementation of multiple interfaces, this interface should
     * be easy to add to any existing mailer, and the inclusion of this method will allow
     * for your current email class to be utilized for logging emails.
     * 
     * @param array  $recipients The recipients of the email message.
     * @param string $subject    The subject of the email message.
     * @param array  $headers    The headers of the email message.
     * @param string $body       The body of the email message.
     * 
     * @return bool
     */
    public function sendLogMail(array $recipients, $subject, array $headers, $body);
}