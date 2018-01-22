<?php
    // Assumes a $record variable is set containing a TrackedRequest object
    // Output the tracking info as a row in a table - assumes within table container
?>
            <tr>
                <td>
                    <?php echo $record->timestamp; ?>
                </td>
                <td>
                    <?php echo $record->trackingkey; ?>
                </td>
                <td>
                    <?php echo $record->ip_addr; ?>
                </td>
                <td>
                    <?php echo $record->user_agent; ?>
                </td>
                <td>
                    <?php echo $record->track_type; ?>
                </td>
                <td>
                    <?php echo $record->url; ?>
                </td>
            </tr>
                    
                    
