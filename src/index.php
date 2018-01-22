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

require_once 'app/app-init.php';
require_once 'app/model/class-frontcontroller.php';
 
$frontController = new FrontController();
$frontController->run();

?>