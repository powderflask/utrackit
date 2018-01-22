<?php
/**
 * Email Tracker - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Script to track a requet for a web beacon.
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */

require_once 'model/class-trackedrequest.php';

// Track the current request...
$request_info = TrackedRequest::trackRequest('beacon');
if ($request_info) {
    Msg::addMessage("Tracked beacon for key: " . $request_info['trackingkey']);
}

// Respond with the beacon image...
// load the image
$image = 'img/pixel.gif';
// getimagesize will return the mimetype for this image
$imageinfo = getimagesize($image);
$image_mimetype = $imageinfo['mime'];

// tell the browser to expect an image
header('Content-type: '.$image_mimetype);
// send it an image
echo file_get_contents($image);

?>