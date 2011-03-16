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
	$db->exec( 'CREATE TABLE IF NOT EXISTS sites ( name TEXT, url TEXT, time INT, weight INT, run BOOL DEFAULT 1, display TEXT DEFAULT "none" )' );
	$db->exec( 'CREATE TABLE IF NOT EXISTS actions ( text TEXT )' );
	$insert = $db->prepare( 'INSERT INTO actions (text) VALUES (:text)' );
	foreach ( $actions as $action ) {
		$insert->execute( $action );
	}
	$insert = $db->prepare( 'INSERT INTO sites (name,url,time,weight) VALUES (:name,:url,:time,:weight)' );
	foreach ( $sites as $site ) {
		$insert->execute( $site );
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
$selectSites = $db->prepare( 'SELECT rowid,* FROM sites' );
$selectSites->execute();
$selectActions = $db->prepare( 'SELECT rowid,* FROM actions' );
$selectActions->execute();
$selectDisplay = $db->prepare( 'SELECT * FROM sites WHERE display=:display LIMIT 1' );
$displays = array();
foreach ( array( 'top-left', 'top-right', 'bottom-left', 'bottom-right' ) as $location ) {
	$selectDisplay->execute( array( ':display' => $location ) );
	$displays[$location] = $selectDisplay->fetch( PDO::FETCH_ASSOC );
}
?>
<!doctype html>
<html>
	<head>
		<base href="http://<?php echo $_SERVER['HTTP_HOST'] . dirname( dirname( $_SERVER['SCRIPT_NAME'] ) ) ?>/" />
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
				<li><a href="#displays">Displays</a></li>
				<li><a href="#sites">Sites</a></li>
				<li><a href="#actions">Actions</a></li>
			</ul>
			<div id="displays" align="center">
				<table>
					<tbody>
						<tr>
							<td id="display-top-left" width="50%">
								<?php if ( $displays['top-left'] ): ?>
								<a href="<?php echo $displays['top-left']['url'] ?>" target="_blank">
									<?php echo $displays['top-left']['name'] ?>
								</a>
								<?php else: ?>
								<a href="#displays" class="blank">
									<img src="images/loading.gif" border="0" align="absmiddle" />
								</a>
								<?php endif; ?>
							</td>
							<td id="display-top-right" width="50%">
								<?php if ( $displays['top-right'] ): ?>
								<a href="<?php echo $displays['top-right']['url'] ?>" target="_blank">
									<?php echo $displays['top-right']['name'] ?>
								</a>
								<?php else: ?>
								<a href="#displays" class="blank">
									<img src="images/loading.gif" border="0" align="absmiddle" />
								</a>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td id="display-bottom-left" width="50%">
								<?php if ( $displays['bottom-left'] ): ?>
								<a href="<?php echo $displays['bottom-left']['url'] ?>" target="_blank">
									<?php echo $displays['bottom-left']['name'] ?>
								</a>
								<?php else: ?>
								<a href="#displays" class="blank">
									<img src="images/loading.gif" border="0" align="absmiddle" />
								</a>
								<?php endif; ?>
							</td>
							<td id="display-bottom-right" width="50%">
								<?php if ( $displays['bottom-right'] ): ?>
								<a href="<?php echo $displays['bottom-right']['url'] ?>" target="_blank">
									<?php echo $displays['bottom-right']['name'] ?>
								</a>
								<?php else: ?>
								<a href="#displays" class="blank">
									<img src="images/loading.gif" border="0" align="absmiddle" /> 
								</a>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div id="sites">
				<table>
					<thead>
						<tr>
							<th>Run</th>
							<th>Name</th>
							<th>URL</th>
							<th>Display</th>
							<th class="numeric">Time</th>
							<th class="numeric">Weight</th>
						</tr>
					</thead>
					<tbody>
						<?php while ( $site = $selectSites->fetch( PDO::FETCH_ASSOC ) ): ?>
						<tr>
							<td>
								<input type="checkbox" id="sites-run-<?php echo $site['rowid'] ?>"
									rel="<?php echo $site['rowid'] ?>" <?php echo $site['run'] ? 'checked' : '' ?> />
								<label for="sites-run-<?php echo $site['rowid'] ?>">Run</label>
							</td>
							<td><?php echo $site['name'] ?></td>
							<td>
								<a href="<?php echo $site['url'] ?>" target="_blank"><?php echo $site['url'] ?></a>
							</td>
							<td><?php echo $site['display'] ?></td>
							<td class="numeric"><?php echo round( $site['time'] / 60000, 2 ) ?> min</td>
							<td class="numeric"><?php echo $site['weight'] ?></td>
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
						<?php while ( $action = $selectActions->fetch( PDO::FETCH_ASSOC ) ): ?>
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