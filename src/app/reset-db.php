<?php
/**
 * Email Tracker - tracking technologies tutorial
 * -------------------------------------------
 * 
 * Script to reset parts of the DB.
 *  
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */
 
/**
 * Figure out which parts of the DB to reset from the URL parameters.
 */
$all = TRUE;

if ($all) {
    require_once 'model/class-trackedrequest.php';
    TrackedRequest::createTable(TRUE);
    Msg::addMessage("ALL DB tables were cleared and reset to intial defaults.");
}

// ... and, go back to the page the user was on.
RedirectBack();
?>