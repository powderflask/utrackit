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

class DB {
    var $host= 'localhost';
    var $dbname= 'emailtracker';
    var $dbuser= 'emailtracker';
    var $dbpw= 'thisisnotsecure';
    var $connection = null;

    /**
     * Main access point for clients:  DB::getConnection()
     * Singleton pattern.
     * returns a mysqli DB connection object, or Null.
     */
    static function getConnection() {
        // The DB object (singleton)
        static $db = null;
        if ($db === null) {
            $db = new DB();

            // Create connection
            $db->connection = new mysqli($db->host, $db->dbuser, $db->dbpw, $db->dbname);
            
            // Check connection
            if ($db->connection->connect_error) {
              Msg::addMessage("Failed to connect to MySQL: " . $db->connection->connect_error, Message::MSG_ERROR);
              $db->connection = null;
            }
            // close the DB connection when the script is done.
            register_shutdown_function(array($db, 'shutdown'));   
        }
        return $db;
    }
    
    /**
     * Is this DB object connected?
     */
    function isConnected() {
        return $this->connection != null;    
    }
    
    /**
     * Make the given query using a prepared statement and put log any error messages.
     * Returns the query result or null
     */
     function query($query, $parameter=null, $logErrors=TRUE) {
         $result = null;
         if ($this->isConnected()) {
             $result = $this->connection->query($query);
             if (!$result) {
                 Msg::addMessage("DB Query failed: " . $query, $this->connection->error, Message::MSG_ERROR);
             }
             return $result;
         }
             // TODO: upgrade to used properly escapted paraemters in prepared statements.
//             $stmt = $this->connection->prepare($query);
//             if (! $stmt) {
//                 Msg::addMessage("DB Query failed: " . $query, $this->connection->error, MSG_ERROR);
//                 return null;
//             }
//             if ($parameter) {
//                 // SANITIZE the parameter to prevent most injection attacks!!
//                 $param = $this->connection->real_escape_string ( $parameter );
//                 $stmt->bind_param("s", $param);
//             }
//             $stmt->execute();
//             $result = $stmt->get_result();
//             $stmt->close();
//             if ($this->connection->errno && $logErrors) {
//                 Msg::addMessage("DB Query failed: " . $this->connection->error, MSG_ERROR);
//                 $result = null;
//             }
//         }
//         return $result;
     }

    /**
     * Constructor - singleton pattern
     */
    protected function __construct() {
    }
    
    /**
     * Disconnect - this function is called automatically when script ends.
     */
    function shutdown() {
        if ($this->isConnected())
            $this->connection->close();
    }    
}  // end DB class
?>