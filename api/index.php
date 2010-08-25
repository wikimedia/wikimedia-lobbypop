<?php
	$db = new PDO( 'sqlite:../data/lobbypop.db' );
	if ( isset( $_GET['query'] ) ) {
		header( 'Content-type: text/javascript' );
		// Return a random site
		$stmt = $db->prepare( 'SELECT sites.url, sites.time, actions.text FROM sites, actions ORDER BY RANDOM() LIMIT 1' );
		$stmt->execute();
		echo json_encode( $stmt->fetch( PDO::FETCH_ASSOC ) );
		exit;
	}
?>