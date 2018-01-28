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
    var $dbname= 'sqlite.db';
    var $pdo = null;

    /**
     * Main access point for clients:  DB::getConnection()
     * Singleton pattern.
     * returns a DB object with an open PDO connection , or Null.
     */
    static function getConnection() {
        // The DB object (singleton)
        static $db = null;
        if ($db === null) {
            $db = new DB();

            // Create connection
            try {
                $db->pdo = new \PDO("sqlite:" . $db->dbname);
                // DB connection will close automatically when $db->pdo object is destoyed (i.e., when the script is done).
            } catch (\PDOException $e) {
                Msg::addMessage("Failed to connect to the SQLite database! ".$e, Message::MSG_ERROR);
                $db->pdo = null;
            }
        }
        return $db;
    }
    
    /**
     * Is this DB object connected?
     */
    function isConnected() {
        return $this->pdo != null;
    }
    
    /**
     * Make the given query and put log any error messages.
     * Returns the query result or null
     * TODO: perform basic data cleaning on parameters and prepare query.
     */
     function query($query, $parameter=null, $logErrors=TRUE) {
         $result = null;
         if ($this->isConnected()) {
             try {
                 $result = $this->pdo->query($query);
             } catch (\PDOException $e) {
                 Msg::addMessage("DB Query failed: " . $query, $e, Message::MSG_ERROR);
             }
         }
         return $result;
     }

    /**
     * Return an array of rows from the given query result.
     * result is an array of rows (of PDO type specified) returned from query
     */
    static function fetch_rows($result, $pdo_type=\PDO::FETCH_OBJ) {  // $pdo_type=\PDO::FETCH_ASSOC for assoc. array
        $rows = [];
        if ($result) {
            while ($row = $result->fetch($pdo_type)) {
                $rows[] = $row;
            }
        }
        // print_r($rows);
        return $rows;
    }

    /**
     * Make the given query and put log any error messages.
     * No results returned (e.g., Create or Delete)
     */
    function exec($query, $parameter=null, $logErrors=TRUE) {
        $result = null;
        if ($this->isConnected()) {
            try {
                $this->pdo->exec($query);
            } catch (\PDOException $e) {
                Msg::addMessage("DB Query failed: " . $query, $e, Message::MSG_ERROR);
            }
        }
    }

    /**
     * Constructor - singleton pattern
     */
    protected function __construct() {
    }
    
}  // end DB class
?>