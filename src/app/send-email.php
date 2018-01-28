<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Script to send an email containing a web beacon tracked by the track-beacon script
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */

require_once 'model/class-email.php';

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $page = 'app/templates/email_form.html';
        require 'app/templates/page_template.php';
        break;
    case 'POST':
        // construct the email
        $email = new Email($_POST['recipient'], $_POST['sender']);

        // send the email
        if ($email->send() == Email::SEND_OK) {
            Msg::addMessage("Email sent to " . $email->recipient . "<br /> with tracking key: " . $email->tracking_key);
        } else {
            Msg::addMessage("Email was not sent.  An error occurred.");
        }

        //        echo($email->message_html);

        // ... and, go back to the page the user was on.
        Redirect(rewriteURL(WEB_BEACON));
        break;
}
?>