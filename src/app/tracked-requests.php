<?php
   require_once 'model/class-trackedrequest.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Hacker Cracker Email Tracker</title>
        <meta name="description" content="List the tracker's collection.">
        <style>
            body {
                background: black url('http://www.wallpaperup.com/uploads/wallpapers/2014/01/07/219176/7a5a19df988856cb8f1276e283a015eb.jpg');
                color: white;
            }
            a {
                color: yellow;
            }
            table, th, td {
                border: 1px solid #00FF00;
                border-collapse:collapse;
            }
            th, td {
                padding: 5px 10px;
            }
        </style>
     </head>
    <body>
        <h1>Welcome to Hacker Cracker Email Tracker</h1>
        <table>
          <tbody>
            <tr><th>Date</th><th>Tracking Key</th><th>IP Addr</th><th>User Agent</th><th>Tracking Type</th><th>Request URL</th></tr>
            <?php TrackedRequest::listAll() ?>
          </tbody>            
        </table>

        <h3>
            <?php
            $path = rewriteURL(WEB_BEACON);
            echo('<a href="'. $path .'" title="Web Beacon">Back to Web Beacon Tracker page</a>');
            ?>
        </h3>
        <h2>Of course....</h2>
        <p>This database would never be revealed to those being tracked!
            Its very existence would likely remain unknown to anyone but the trackers.
        </p>
        <p>
            The data about which individual is identified by each tracking code has been kept hidden for privacy reasons.
            However, since these codes were created with an email address, it is a trivial matter to indentify the person,
            their email address, and any other personal information we have about them, back to each unique tracking code id.
        </p>
    </body>
</html>