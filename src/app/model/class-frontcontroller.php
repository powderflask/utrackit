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

 class FrontController
{
    const FRONT_SCRIPT       = "index.php";
    const DEFAULT_CONTROLLER = "app";
    const DEFAULT_ACTION     = "home";
    const DEFAULT_PATH       = HOME;
     
    protected $controller    = self::DEFAULT_CONTROLLER;
    protected $action        = self::DEFAULT_ACTION;
    protected $params        = array();
    protected $path          = self::DEFAULT_PATH;
     
    public function __construct() {
           $this->parseUri();
    }
    
    /**
     * Decompose the URI, assumed to be in the form:  http::/domain.name/controller/action/param1/param2
     */ 
    protected function parseUri() {
        // If site uses server with no URL rewrite capabilities, configure setting in app-init
        // to put all URL's into the ?q='path' query parameter.
        if (isset($_GET['q'])) {
            $path = $_GET['q'];
        }
        else { // it would be preferable to use .htaccess to re-write URL's and get the path thusly... *sigh*
            $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
        }
        
        if ($path == self::FRONT_SCRIPT)
            $path = '';  // go with default path
           
        if ($path) {
            @list($this->controller, $this->action, $this->params) = explode("/", $path, 3);
            $this->path = $path;        
        }
    }
    
    /**
     * "Run" the requested action
     * In this very simple controller, that means load the appropriate page template or php script.
     */
    public function run() {        
        global $TRACKER_SCRIPTS;
        
        $action = $this->action;
        $is_script = $this->controller==self::DEFAULT_CONTROLLER && in_array ( $action , $TRACKER_SCRIPTS );
        $is_tracker = $this->controller == 'trackers';

        $page = $this->path . '.html';
        
        // Normal case - we route the request to the corresponding ".html" page template
        if (file_exists ( $page )) {

            include 'app/templates/page_template.php';
          
        // Special handling for scripts   
        } else if ( $is_script ) {
            $script_path = $this->path . '.php';
            include $script_path;
            
        // Anything else is a 404
        } else {
            header("HTTP/1.0 404 Not Found");
            include '404.html';
        }
    }
}