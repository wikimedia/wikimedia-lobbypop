<?php

//header( 'Content-type: application/xml' );

echo file_get_contents(
	'http://en.wikipedia.org/w/index.php?title=Special:RecentChanges&limit=1&feed=rss',
	false,
	stream_context_create( array( 'http' => array( 'header'=> 'User-Agent: LobbyPop!' ) ) )
);

?>