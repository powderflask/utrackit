<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -----------------------------------------
 * 
 * Simple tracking cookies
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */

require_once 'util.php';
require_once 'class-trackedrequest.php';

define ('TRACK_DURATION', 60*60*24*90);  // track for 3 months

 /**
  * An individual tracking cookie.
  * By default, each cookie keeps a count of how many times it has been requested
  */
class Cookie {

    function __construct($name, $page_specific=false, $is_date=false, $reset=false) {
        $path = $page_specific ? RequestURI() : null;
        $expiry = time()+TRACK_DURATION;
        $value = null;
        if(isset($_COOKIE[$name]) and !$reset) { // we have a cookie, use its value.
            $value = $_COOKIE[$name];
            if (!$is_date)
                $value++;  // counters get incremented, dates just stay fixed.
        }
        else {  // brand new cookie or cookie reset - initialize values
            $value = $is_date ? time() : 1;  // date starts now, counters start at 1
        }

        setcookie($name, $value, $expiry, $path);
    }

    static function track() {
        // Don't track visits to the "hackers DB"
        if (strpos(RequestURI(), HACKER_SITE))
            return;

        // Track:
        // Site visits & initial & last visit date/time
        new Cookie('site_tracker');
        new Cookie('first_visit', false, true);
        new Cookie('last_visit', false, true, true);

        // Page visits
        new Cookie(RequestURI(), true);

        TrackedRequest::trackRequest("cookie");
    }
}

?>