<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -----------------------------------------
 * 
 * Simple messaging class
 *   - stores serialized messages directly in session cookie.
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */


 /**
  * An individual message.
  */
class Message {
    const MSG_INFO = 'alert-info';
    const MSG_SUCCESS = 'alert-success';
    const MSG_WARN ='alert-warning';
    const MSG_ERROR = 'alert-danger';

    var $msg = '';
    var $lvl = self::MSG_INFO;
    
    function __construct($message, $level= self::MSG_INFO) {
        $this->msg = $message;
        $this->lvl = $level;
    }
}

class Msg {    
    /**
     * Init. - must be called before attempting to store messages in session.
     */    
    protected static function initSession() {
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = array();
        }
    }
    /**
     * Clear - call when messages have been printed.
     */    
    protected static function clearSession() {
        unset($_SESSION['messages']);
    }
    
    static function addMessage($message, $level=Message::MSG_INFO) {
        Msg::initSession();
        $_SESSION['messages'][] = serialize(new Message($message, $level));       
    }
    
    static function printMessages() {
        if (isset($_SESSION['messages']) && count($_SESSION['messages'])>0) {
            echo '<div class="messages">';
            foreach ($_SESSION['messages'] as $smsg) {
                $message = unserialize($smsg);
                echo '<div class="alert '. $message->lvl .'">'. $message->msg .'</div>';
            }
            echo '</div>';
        }
        Msg::clearSession();
    }
}

?>