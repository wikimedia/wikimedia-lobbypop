<?php
// Handle reset
if ( isset( $_POST['reset'] ) ) {
	if ( file_exists( '../data/lobbypop.db' ) ) {
		unlink( '../data/lobbypop.db' );
	}
	header( 'Location: ./' );
}
// Automatically bootstrap database
if ( !file_exists( '../data/lobbypop.db' ) ) {
	// Include initial dataset
	require_once( 'bootstrap.php' );
	// Create and initialize database
	$db = new PDO( 'sqlite:../data/lobbypop.db' );
	$db->exec( 'CREATE TABLE IF NOT EXISTS sites ( url TEXT, time INT, weight INT, run BOOL DEFAULT 1, display TEXT DEFAULT "none" )' );
	$db->exec( 'CREATE TABLE IF NOT EXISTS actions ( text TEXT )' );
	$stmt = $db->prepare( 'INSERT INTO actions (text) VALUES (:text)' );
	foreach ( $actions as $action ) {
		$stmt->execute( $action );
	}
	$stmt = $db->prepare( 'INSERT INTO sites (url,time,weight) VALUES (:url,:time,:weight)' );
	foreach ( $sites as $site ) {
		$stmt->execute( $site );
	}
}
// Automatically connect to the database
if ( !isset( $db ) ) {
	$db = new PDO( 'sqlite:../data/lobbypop.db' );
}
// Handle actions
if ( isset( $_POST['action'] ) ) {
	$response = array( 'status' => 'ok' );
	try {
		switch ( $_POST['action'] ) {
			case 'run':
				$run = $db->prepare( 'UPDATE sites SET run=:run WHERE rowid=:rowid' );
				$run->execute( array( ':run' => $_POST['run'], ':rowid' => $_POST['site'] ) );
				break;
			default:
				throw new Exception( 'Invalid action: ' . $_POST['action'] );
		}
	} catch ( Exception $exception ) {
		$response['status'] = 'error';
		$response['message'] = $exception->getMessage();
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $response );
	exit;
}
// Run queries
$sites = $db->prepare( 'SELECT rowid,* FROM sites' );
$sites->execute();
$actions = $db->prepare( 'SELECT rowid,* FROM actions' );
$actions->execute();
?>
<!doctype html>
<html>
	<head>
		<base href="http://<?php echo $_SERVER['SERVER_NAME'] . dirname( dirname( $_SERVER['SCRIPT_NAME'] ) ) ?>/" />
		<title>LobbyPop! Admin</title>
		<link rel="stylesheet" href="styles/smoothness/jquery-ui-1.8.4.css" />
		<link rel="stylesheet" href="styles/admin.css" />
	</head>
	<body>
		<div id="logo"><img src="images/logo.png" border="0" alt="LobbyPop!" title="LobbyPop!" /></div>
		<div id="menu">
			<form action="./admin/" method="post">
				<button type="submit" id="reset" name="reset">Reset</button>
			</form>
		</div>
		<div id="tabs">
			<ul>
				<li><a href="#sites">Sites</a></li>
				<li><a href="#actions">Actions</a></li>
			</ul>
			<div id="sites">
				<table>
					<thead>
						<tr>
							<th>Run</th>
							<th>URL</th>
							<th>Time</th>
							<th>Weight</th>
							<th>Display</th>
						</tr>
					</thead>
					<tbody>
						<?php while ( $site = $sites->fetch( PDO::FETCH_ASSOC ) ): ?>
						<tr>
							<td>
								<input type="checkbox" id="sites-run-<?php echo $site['rowid'] ?>"
									rel="<?php echo $site['rowid'] ?>" <?php echo $site['run'] ? 'checked' : '' ?> />
								<label for="sites-run-<?php echo $site['rowid'] ?>">Run</label>
							</td>
							<td>
								<a href="<?php echo $site['url'] ?>" target="_blank"><?php echo $site['url'] ?></a>
							</td>
							<td><?php echo $site['time'] ?></td>
							<td><?php echo $site['weight'] ?></td>
							<td><?php echo $site['display'] ?></td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
			<div id="actions">
				<table>
					<thead>
						<th>Text</th>
					</thead>
					<tbody>
						<?php while ( $action = $actions->fetch( PDO::FETCH_ASSOC ) ): ?>
						<tr>
							<td><?php echo $action['text'] ?></td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
		<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.8.4.min.js"></script>
		<script type="text/javascript" src="scripts/admin.js"></script>
	</body>
</html>