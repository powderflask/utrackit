# UTrackIt : Web Tracking Tech Demo

An interactive tutorial to demonstrate common web tracking technologies

-------------------------------------------

The purpose of this project is to demonstrate how common web tracking technologies
work to undermine the expectation of privacy many people have when browsing the web or reading email.

This code should NOT, under any circumstance, be used on a real website for any purpose.

 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt

INSTALLATION:
-------------
 System Requirements:  PHP 5+ with Sqlite3;  mod_rewrite optional - see (2)

 1) Install the app in htdocs, and point your browser at index.php
    An squlite DB will be created in the src folder

 2) If you are using .htaccess mod_rewrite, you can (by default) use clean URL
    Otherwise, if your server does not do re-writes, ensure CLEAN_URLS is FALSE in app-init.php.

TO DO:
------
 - add "3rd party cookie" tracker

DEVELOPMENT:
------------
Code is available at: https://github.com/powderflask/utrackit

Contributions welcome on following conditions:
 - this is a BASIC tutorial - examples should be aimed at students just learning about web privacy
 - each tracker is a simple lesson that allows student to explore how a specific technology works - these are not challenges, they are lessons!

How to build a new tracker page:
 - add a new "template" to the trackers folder to describe the tracker and how it works
 - add a tracker item in app-init.php
 - any new php files should be added to app/ folder

