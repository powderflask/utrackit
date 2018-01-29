<?php
    // Assumes tracking cookies have been set object
    // Output the tracking cookie info
?>
    <h4>Site / Page visit data</h4>
    <table class="table">
        <tr>
            <th>Data being tracked</th> <th>Your Tracked Info</th>
        </tr>
        <tr>
            <td>Site first visited</td><td><?php echo date('Y-m-d H:i:s', $_COOKIE['first_visit']); ?> UTC</td>
        </tr>
        <tr>
            <td>Last visit to site</td><td><?php echo date('Y-m-d H:i:s', $_COOKIE['last_visit']); ?> UTC</td>
        </tr>
        <tr>
            <td># pages visited on site</td><td><?php echo $_COOKIE['site_tracker'] ?></td>
        </tr>
        <tr>
            <td># times current page visited</td><td><?php echo $_COOKIE[$_SERVER['REQUEST_URI']] ?></td>
        </tr>
    </table>
    <h4>Browser / Location data</h4>
    <table class="table">
        <tr>
            <th>Browser Data being tracked</th> <th>Your Tracked Info</th>
        </tr>
        <tr>
            <td>Browser type / version</td><td><?php echo $_SERVER['HTTP_USER_AGENT'];?></td>
        </tr>
        <tr>
            <td>IP Address (Location)</td><td><?php echo $_SERVER['REMOTE_ADDR'];?></td>
        </tr>
        <tr>
            <td>Refering Page</td><td><?php echo $_SERVER['HTTP_REFERER'];?></td>
        </tr>
    </table>

                    
