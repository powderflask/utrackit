<?php
    include 'header.html';
        
    // print any messages generated during this request
    Msg::printMessages();

    include $page;
    
    include 'footer.html';
 
?>