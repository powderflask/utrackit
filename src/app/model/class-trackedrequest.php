<?php
/**
 * UTrackIt - tracking technologies tutorial
 * -------------------------------------------
 *
 *   A TrackedRequest object represents a single request that is tracked in the app DB.
 *
 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt
 */
require_once 'class-db.php';
require_once 'util.php';

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
     * TODO: pass parameters separate and use prepared statement
     */
    static function add($trackingkey, $user_agent, $ip_addr, $track_type, $url) {
        $db = DB::getConnection();
        if ($db->isConnected()) {
            // Rely on MySQL to auto_increment id and use current_timestamp for datetime
            $query="INSERT INTO trackedrequests (trackingkey, user_agent, ip_addr, track_type, url)
                    VALUES ('$trackingkey', '$user_agent', '$ip_addr', '$track_type', '$url')";

            $db->exec($query);
        }
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
    public static function fetchAll($track_type=null) {
        $tracked = array();
        $db = DB::getConnection();
        $where = $track_type ? "WHERE track_type='$track_type'" : "";

        if ($db->isConnected()) {
            $query="SELECT * FROM trackedrequests " . $where .
                           " ORDER BY trackingkey ASC, timestamp DESC";
            // Run query
            $result = $db->query($query);
            $tracked = DB::fetch_rows($result);
        }
        return $tracked;
    }

    /**
     * Echo all tracked requests to the response stream
     */
    public static function listAll($track_type=null) {
        $tracked = self::fetchAll($track_type);
        foreach ($tracked as $record) {
            include 'app/templates/tracked_record-template.php';
        }
    }

    /**
     * Handle a new request with a tracking key
     */
    public static function trackRequest($trackingtype='?', $url=null) {
        // Collect data about the request

        $session_id = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : null;
        $trackingkey = isset($_GET['tk']) ? urldecode($_GET['tk']) : $session_id;  // TODO: GET param keys define as const
        if ($url == null) {
            $url = isset($_SERVER['REQUEST_URI']) ? rel2abs(RequestURI()) : '';
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
    public static function createTable($deleteFirst = FALSE)
    {
        $db = DB::getConnection();
        if ($db->isConnected()) {
            if ($deleteFirst) {
                $db->exec("DROP TABLE IF EXISTS trackedrequests");
            }
            // Only do the create if the table does not yet exist.
            $query = "CREATE TABLE IF NOT EXISTS trackedrequests (
                              id INTEGER PRIMARY KEY NOT NULL,
                              trackingkey text NOT NULL,
                              timestamp timestamp default CURRENT_TIMESTAMP NOT NULL,
                              user_agent text,
                              ip_addr text,
                              track_type text,
                              url text
                            )
                     ";

            $db->exec($query);
            return true;
        } else {
            return false;
        }
    }
}
?>