<?php
// Handle AJAX Requests
if ( isset( $_GET['query'] ) ) {
	try {
		$db = new PDO( 'sqlite:data/lobbypop.db' );
		header( 'Content-type: application/json' );
		// Try for a random unused one
		$select = $db->query( 'SELECT sites.rowid, sites.url, sites.time, actions.text FROM sites, actions WHERE sites.run = 1 AND sites.display = "none" ORDER BY RANDOM() LIMIT 1' );
		$response = $select->fetch( PDO::FETCH_ASSOC );
		// Just use a random one
		if ( !$response ) {
			$select = $db->query( 'SELECT sites.rowid, sites.url, sites.time, actions.text FROM sites, actions WHERE sites.run = 1 ORDER BY RANDOM() LIMIT 1' );
			$response = $select->fetch( PDO::FETCH_ASSOC );
		}
		// Detach previous site from display
		$update = $db->prepare( 'UPDATE sites SET display = "none" WHERE display = :display' );
		$update->execute( array( ':display' => $_GET['query'] ) );
		// Attach new site to display
		$update = $db->prepare( 'UPDATE sites SET display = :display WHERE rowid = :rowid' );
		$update->execute( array( ':display' => $_GET['query'], ':rowid' => $response['rowid'] ) );
		echo json_encode( array_merge( array( 'status' => 'ok' ), $response ) );
	} catch ( Exception $exception ) {
		echo json_encode( array( 'status' => 'error', 'message' => $exception->getMessage() ) );
	}
	exit;
}
// Handle multiple rendering modes
$frameset = isset( $_GET['frameset'] ) ? $_GET['frameset'] : null;
$display = isset( $_GET['display'] ) ? $_GET['display'] : null;
?>
<!doctype html>
<html>
	<head>
		<title>LobbyPop!</title>
		<?php if ( isset( $frameset, $display ) ): ?>
		<link rel="stylesheet" href="styles/display.css" media="all" />
		<?php elseif ( isset( $frameset ) ): ?>
		<link rel="stylesheet" href="styles/frameset.css" media="all" />
		<?php else: ?>
		<link rel="stylesheet" href="styles/setup.css" media="all" />
		<?php endif; ?>
	</head>
	<body>
		<?php if ( isset( $frameset, $display ) ): ?>
		<iframe id="screen" rel="<?php echo implode( '-', array( $frameset, $display ) ) ?>"></iframe>
		<div id="overlay">
			<div id="label">
				<img src="images/logo.png" border="0" alt="LobbyPop!" title="LobbyPop!" />
			</div>
		</div>
		<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="scripts/display.js"></script>
		<?php elseif ( isset( $frameset ) ): ?>
		<iframe src="index.php?frameset=<?php echo $frameset ?>&display=left" id="left" frameborder="0"></iframe>
		<iframe src="index.php?frameset=<?php echo $frameset ?>&display=right" id="right" frameborder="0"></iframe>
		<?php else: ?>
		<img src="images/logo.png" border="0" alt="LobbyPop!" title="LobbyPop!" />
		<div id="screens">
			<a href="index.php?frameset=top">Top</a>
			<a href="index.php?frameset=bottom">Bottom</a>
		</div>
		<?php endif; ?>
	</body>
</html>