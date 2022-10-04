<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -----------------------------------------
 *
 *   The purpose of this code is to demonstrate common email tracking technologies work.
 *
 *   This code should NOT, under any circumstance, be used on a real website.
 *
 *
 *   Configuration settings, request routing, and basic app initialization
 *
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */

    // Start the session  (something sensible :-P )
    session_start();

    // If you use a server with no URL rewrite capabilities (e.g., IIS) - this flag causes URL paths to be sent as a ?q= paramter
    define ('CLEAN_URLS', TRUE);
    define ('USE_QUERY_PATH', ! CLEAN_URLS);

    // crude routing information for scripts and pages
    define ('HOME', 'app/home');
    define ('ABOUT', 'app/about');
    define ('HACKER_SITE', 'app/tracked-requests');
    // form / data processing scripts
    define ('TRACK_CLICK', 'app/track-click');
    define ('TRACK_BEACON', 'app/track-beacon');
    define ('SEND_EMAIL', 'app/send-email');
    define ('RESET-DB', 'app/reset-db');
    // data processing scripts, routed differently than regular "pages"
    $TRACKER_SCRIPTS = array('track-beacon', 'track-click', 'send-email', 'tracked-requests', 'reset-db');
        
    // Tracker page base names used for special processing done for specific trackers
    define ('CLICK_TRACKER', 'app/click-tracker');
    define ('WEB_BEACON', 'app/web-beacon');
    define ('COOKIE_TRACKER', 'app/cookie-tracker');

    // Crude routing for the Tracker pages:
    // To add a new Tracker:
    //  1) add an entry to this array (tracker url matches tracker html template name!)
    //  2) add .html page to tracker templates folder to demonstrate the tracker
    $TRACKERS = array (
        CLICK_TRACKER  => array('path' => CLICK_TRACKER,  'name' => 'Click Tracker', 'track_type' => 'click'),
        WEB_BEACON  => array('path' => WEB_BEACON,  'name' => 'Web Beacon', 'track_type' => 'beacon'),
        COOKIE_TRACKER  => array('path' => COOKIE_TRACKER,  'name' => 'Cookie Tracker', 'track_type' => 'cookie'),
    );

    // URL's and Anchors required for tracking and viewing tracked data
    define('TRACKING_URL', rel2abs( TRACK_BEACON, siteURL() ) );
    define('CLICK_TRACKING_URL', rel2abs( TRACK_CLICK, siteURL() ) );
    define('HACKERS_URL', rel2abs( HACKER_SITE, siteURL() ) );
    define('HACKERS_ANCHOR', "<a href='".HACKERS_URL."' title='Tracking data'>".HACKERS_URL."</a>");

    require_once 'model/class-cookie.php';
    require_once 'model/class-msg.php';
    require_once 'model/util.php';

    // Manage tracking Cookies
    Cookie::track();