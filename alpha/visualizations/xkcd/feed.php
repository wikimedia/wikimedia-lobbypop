<?php

header( 'Content-type: application/xml' );
echo file_get_contents( 'http://xkcd.com/rss.xml' );

?>