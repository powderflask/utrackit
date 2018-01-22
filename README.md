# Email Tracker Demo

An interactive tutorial to demonstrate common email tracking technologies

-------------------------------------------

The purpose of this project is to demonstrate how common email tracking technologes
work to undermine the expectation of privacy many people have when reading email.

This code should NOT, under any circumstance, be used on a real website for any purpose.

 * Version: 0.1
 * Author: Driftwood Cove Designs
 * Author URI: http://driftwoodcove.ca
 * License: GPL3 see license.txt

INSTALLATION:
-------------
 1) Create a MySQL DB  (other DBMS may work, but not tested)
    - edit DB name, user, p/w into class-db.php
    - all tables are created by app as required.

 2) If you are using .htaccess mod_rewrite, you can (optionally) use clean URL's setting in app-init.php
    Otherwise, ensure CLEAN_URLS is FALSE in app-init.php (default setting for use on hack.me).

 3) Install the app in htdocs, and point your browser at index.php


DEVELOPMENT:
------------
Code is available at: https://github.com/powderflask/email-tracker

Contributions welcome on following conditions:
 - this is a BASIC tutorial - examples should be aimed at students just learning about Internet privacy
 - each tracker is a simple lesson that allows student to explore how a specific technology works - these are not challenges, they are lessons!

How to build a new tracker page:
 - add a new "template" to the trackers folder to describe the tracker and how it works
 - add a tracker item in app-init.php
 - any new php files should be added to app/ folder

