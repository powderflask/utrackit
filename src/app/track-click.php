<?php
/**
 * Email Tracker - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Script to track a request for a web page.
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */

require_once 'model/util.php';
require_once 'model/class-trackedrequest.php';

$url = isset($_GET['url']) ? urldecode($_GET['url']) : siteURL();

// Track the current request...
$request_info = TrackedRequest::trackRequest('click', $url);
if ($request_info) {
    Msg::addMessage("Tracked click for key: " . $request_info['trackingkey'] . "<br>at: ". $url);
}

Redirect($url);

?>