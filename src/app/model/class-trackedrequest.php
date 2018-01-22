<?php
/**
 * Email Tracker - tracking technologies tutorial
 * -------------------------------------------
 *
 *   The purpose of this code is to demonstrate common email tracking technologies work.
 *
 *   This code should NOT, under any circumstance, be used on a real website.
 *
 *
 *   This app uses a very simple "Front Controller" pattern to route requests.
 *
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */
require_once 'class-db.php';

// Ensure the TrackedRequest DB table is setup (this is really inefficient - should be relegated to install script)
TrackedRequest::createTable();

class TrackedRequest {
    
    // these fields correspond to the fields in the trackedrequests DB table.
    var $id;       // db key
    var $referer;  // site where session key was hacked from
    var $trackingkey;  // unique key identifying a particular user
    var $timestamp; // date/time session was obtained

    /**
     * Add a request to the DB -- GACK! raw data stored in DB -- pathetic!
     */
    static function add($trackingkey, $user_agent, $ip_addr, $track_type, $url) {
        $db = DB::getConnection();
        if ($db->isConnected()) {
            // Rely on MySQL to auto_increment id and use current_timestamp for datetime
            $query="INSERT INTO trackedrequests (trackingkey, user_agent, ip_addr, track_type, url)
                    VALUES ('$trackingkey', '$user_agent', '$ip_addr', '$track_type', '$url');";

            $result = $db->query($query);
            $result = !$result ? "DB Error adding new request tracking record." : TRUE;
        }
        else {
           $result = "Unable to connect to DB - try later.";
        }
        return $result;
    }

    /**
     * Look to see if a user is already in the DB
     */
    public static function alreadyGrabbed($trackingkey) {
        $sessions = array();
        $db = DB::getConnection();
        if ($db->isConnected()) {
            $query="SELECT * FROM trackedrequests
                             WHERE trackingkey='$trackingkey';";
            // Run query
            $result = $db->query($query);
            return ($result && $result->num_rows > 0);
        }
        return false;
    }

    /**
     * Load all tracked requests from the DB - return empty array if none exist
     */
    public static function fetchAll() {
        $tracked = array();
        $db = DB::getConnection();
        if ($db->isConnected()) {
            $query="SELECT * FROM trackedrequests
                             ORDER BY trackingkey ASC, timestamp DESC;";
            // Run query
            $result = $db->query($query);
            if ($result && $result->num_rows > 0) {
                while ($record = $result->fetch_object('TrackedRequest')) {
                    $tracked[] = $record;
                }
            }
        }
        return $tracked;
    }

    /**
     * Echo all tracked requests to the response stream
     */
    public static function listAll() {
        $tracked = self::fetchAll();
        foreach ($tracked as $record) {
            include 'app/templates/tracked_record-template.php';
        }
    }

    /**
     * Handle a new request with a tracking key
     */
    public static function trackRequest($trackingtype='?', $url=null) {
        // Collect data about the request
        $trackingkey = isset($_GET['tk']) ? $_GET['tk'] : null;  // TODO: GET param keys define as const
        if ($url == null) {
            $url = isset($_SERVER['REQUEST_URI']) ? rel2abs($_SERVER['REQUEST_URI']) : '';
        }
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $ip_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $repeat = FALSE;

        // If there is a tracking key, store request data in DB
        if ($trackingkey) {
            $r = self::add($trackingkey, $user_agent, $ip_addr, $trackingtype, $url);
            //dump_var($r);
            //$repeat = self::alreadyGrabbed($trackingkey);

            return array('trackingkey' => $trackingkey,
                'user_agent' => $user_agent,
                'ip_addr' => $ip_addr,
                'type' => $trackingtype,
                'url' => $url,
                'repeat' => $repeat
            );
        }
        return FALSE;
    }

    /**
     * Create the trackedrequests table in the DB with some default data.
     * If $deleteFirst is TRUE, then any existing trackedrequests table will be deleted first.
     * Otherwise, table is created only if it does not already exist.
     */
    public static function createTable($deleteFirst = FALSE) {
        $db = DB::getConnection();
        if ($db->isConnected()) {
            if ($deleteFirst) {
                $db->query("DROP TABLE IF EXISTS trackedrequests");
            }
            // Only do the create if the table does not yet exist.
            $table_exists = $db->query("DESCRIBE `trackedrequests`;", FALSE);
            if (! $table_exists) {
                $query = "CREATE TABLE `trackedrequests` (
                                  `id` int(10) unsigned NOT NULL auto_increment,
                                  `trackingkey` text character set utf8 NOT NULL,
                                  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
                                  `user_agent` text character set utf8,
                                  `ip_addr` text character set utf8,
                                  `track_type` text character set utf8,
                                  `url` text character set utf8,
                                  PRIMARY KEY  (`id`)
                                ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
                         ";
                $db->query($query);
            }
         }
    }
        
}
?>