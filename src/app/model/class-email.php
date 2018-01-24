<?php
/**
 * Email Tracker - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Basic email class
 *   - configure and send HTML formatted messages.
 *  
 * Version: 0.1
 * Author: backupbrain
 * Author URI: https://github.com/backupbrain/send-html-email-php
 * License: GPL3 see license.txt
 */

class Email {
    var $recipient,
        $sender,
        $subject,
        $message_text,
        $message_html,
        $tracking_key;

    const SEND_OK = TRUE;
    const SEND_FAIL = FALSE;
    const DEFAULT_RECIP = 'Johnny Appleseed <johnnny@example.com>';
    const DEFAULT_SENDER = 'Example Company <no-reply@example.com>';

    public function __construct($recipient=self::DEFAULT_RECIP, $sender=self::DEFAULT_SENDER) {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->tracking_key = sha1($recipient);
        $this->set_default_message();
    }

    public function set_default_message() {
        $this->subject = "This email is being tracked!";
        $this->message_text = "
            Hello!
            Your email client is not showing the HTML version of this email.
            That means you are NOT being tracked!
            Have a great day.
        ";
        $this->message_html = "
            <h1>Hello!</h1>
            <p>This email contains a hidden tracking code. If your email client loaded the remote web beacon in this email, you've been tracked!</p>
            <p>To see the tracking information collected about you, see :".HACKERS_ANCHOR."</p>
            <p>Your email address is associated with tracking code: ".$this->tracking_key."</p>
            <p>This tracking software is for educational purposes only, and none of your personal data has been compromised by this email tracker.</p>
        ";
        $this->add_tracking_link();
        $this->add_tracking_code();
    }

    public function add_tracking_link() {
        $path = CLICK_TRACKING_URL . '?tk='.$this->tracking_key.'&url='.urlencode(siteURL());
        $link = "
            <p>
                <a href='". $path ."' title='Click Tracker'>This link tracks when and where you clicked it!</a>
            </p>
        ";
        $this->message_html = $this->message_html.$link;
    }

    public function add_tracking_code() {
        $this->message_html = $this->message_html."<img src='".TRACKING_URL."?tk=".$this->tracking_key."'>";
    }

        /**
     * Make text rfj2047 compliant
     * Convert HTML character entities into ISO-8859-1,
     * then to Base64 for rfc2047 email subject compatibility.
     */
    public static function rfc2047_sanitize($text) {
        $output = mb_encode_mimeheader(
            html_entity_decode(
                $text,
                ENT_QUOTES,
                'ISO-8859-1'),
            'ISO-8859-1','B',"\n");
        return $output;
    }

    /**
     * Set this Email object
     **/
    public function send( ) {
        // let's create the headers to show where the email
        // originated from.
        $headers[] = 'From: '.$this->sender;
        $headers[] = 'Reply-To: '.$this->sender;



        // Subjects are tricky.  Even some
        // sophisticated email clients don't
        // understand unicode subject lines.
        $subject = self::rfc2047_sanitize($this->subject);

        $message = "";

        $mime_boundary = '<<<--==+X['.md5(time()).']';

        // if the email is HTML, then let's tell the MTA about the mime-type and all that
        if ($this->message_html) {
            // set up a mime boundary so that we can encode
            // the email inside it, hiding it from clients
            // that can only read plain text emails
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: multipart/mixed;';
            $headers[] = ' boundary="'.$mime_boundary.'"';
            $message = $this->message_html;

            $message .= "\r\n";
            $message .= "--".$mime_boundary."\r\n";
        }

        // since this is a mime/multipart message, we need to re-iterate
        // the message contents in order for mime-aware clients to read it
        if ($this->message_html) {
            $message .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n";
            $message .= "Content-Transfer-Encoding: 7bit\r\n";
            $message .= "\r\n";
            $message .= $this->message_html;
        } else {
            $message .= 'Content-type: text/plain; charset=iso-8859-1';
            $message .= "Content-Transfer-Encoding:  7bit\r\n";
            $message .= "\r\n";
            $message .= $this->message_text;
        }
        $message .= "\r\n";
        $message .= "--".$mime_boundary."\r\n";
        $message .= $this->message_text;



        // try to send the email.
        $result = mail( $this->recipient,
            $subject,
            $message,
            implode("\r\n",$headers)
        );

        return $result?self::SEND_OK:self::SEND_FAIL;
    } // send

}

?>