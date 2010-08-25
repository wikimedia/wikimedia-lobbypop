<?php
	
	if ( isset( $_POST['rebuild'] ) ) {
		if ( file_exists( '../data/lobbypop.db' ) ) {
			unlink( '../data/lobbypop.db' );
		}
	}
	if ( !file_exists( '../data/lobbypop.db' ) ) {
		$db = new PDO( 'sqlite:../data/lobbypop.db' );
		$db->exec( 'CREATE TABLE IF NOT EXISTS sites ( url TEXT, time INTEGER )' );
		$db->exec( 'CREATE TABLE IF NOT EXISTS actions ( text TEXT )' );
		$db->exec( 'CREATE TABLE IF NOT EXISTS screens ( name TEXT, url TEXT )' );
		$actions = array(
			'Connecting you up to the matrix',
			'Discombobulating your P-dotter',
			'Tweaking your PRAM and harshing your H-drive',
			'Reading your mind',
			'Imagining everyone in the audience in their underwear',
			'Mowing the lawn',
			'Waiting for the bus',
			'Picking noses',
			'Searching your name in Google',
			'Eating soft cheese',
			'Testing the water',
			'Sticking finger in air',
			'Listening to railroad tracks',
			'Saving server kitties',
			'Emailing sysadmins',
			'Hunting down, and verbally assaulting, spammers',
			'Filtering spam',
			'Ignorning trolls',
			'Predicting the future',
			'Editing your wiki',
			'Selecting new server kittens',
			'Locating the required gigapixels to render',
			'Spinning up the hamster',
			'Shovelling coal into the server',
			'Programming the flux capacitor',
			'Commencing infinite loop (this may take some time)',
			'Downloading all of your interwebs',
			'The cake is a lie',
			'Searching for the any key',
			"I'm not slacking off, my code's compiling",
		);
		$stmt = $db->prepare( 'INSERT INTO actions (text) VALUES (:text)' );
		foreach ( $actions as $text ) {
			$stmt->execute( array( ':text' => $text ) );
		}
		$sites = array(
			// WikiSwarm for 00:01:23
			array( ':url' => 'visualizations/wikiswarm', ':time' => 83000 ),
			// XKCD for 00:02:00
			array( ':url' => 'visualizations/xkcd', ':time' => 120000 ),
			// Anon-edits for 00:10:00
			array( ':url' => 'http://www.lkozma.net/wpv', ':time' => 30000 ),
			// Commons for 00:02:00
			array( ':url' => 'http://toolserver.org/~para/Commons:Special:NewFiles', ':time' => 30000 ),
			// Wikipedia Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWp.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wikibooks Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWb.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wikinews Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWn.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wikiquote Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWq.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wikisource Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWs.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wikiversity Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWv.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
			// Wiktionary Growth for 00:00:30
			array(
				':url' => 'http://stats.wikimedia.org/wikimedia/animations/growth/AnimationProjectsGrowthWk.html?canvas_width=1800&canvas_height=1000',
				':time' => 30000
			),
		);
		$stmt = $db->prepare( 'INSERT INTO sites (url,time) VALUES (:url,:time)' );
		foreach ( $sites as $site ) {
			$stmt->execute( $site );
		}
	}
	if ( !isset( $db ) ) {
		$db = new PDO( 'sqlite:../data/lobbypop.db' );
	}
?>
<!doctype html>

<html>
	<head>
		<title>LobbyPop!</title>
		<script type="text/javascript" src="../scripts/jquery-1.4.2.min.js"></script>
	</head>
	<body>
		<h1>LobbyPop!</h1>
		<pre><?php 
			$stmt = $db->prepare( 'SELECT * FROM sites' );
			$stmt->execute();
			var_dump( $stmt->fetchAll( PDO::FETCH_ASSOC ) );
			$stmt = $db->prepare( 'SELECT * FROM actions' );
			$stmt->execute();
			var_dump( $stmt->fetchAll( PDO::FETCH_COLUMN ) );
		?></pre>
		<form action="index.php" method="post"><button type="submit" name="rebuild">Rebuild</button></form>
	</body>
</html>