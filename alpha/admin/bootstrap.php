<?php 
// List of action messages to display while loading
$actions = array(
	array( ':text' => 'Connecting you up to the matrix' ),
	array( ':text' => 'Discombobulating your P-dotter' ),
	array( ':text' => 'Tweaking your PRAM and harshing your H-drive' ),
	array( ':text' => 'Reading your mind' ),
	array( ':text' => 'Imagining everyone in the audience in their underwear' ),
	array( ':text' => 'Mowing the lawn' ),
	array( ':text' => 'Waiting for the bus' ),
	array( ':text' => 'Picking noses' ),
	array( ':text' => 'Searching your name in Google' ),
	array( ':text' => 'Eating soft cheese' ),
	array( ':text' => 'Testing the water' ),
	array( ':text' => 'Sticking finger in air' ),
	array( ':text' => 'Listening to railroad tracks' ),
	array( ':text' => 'Saving server kitties' ),
	array( ':text' => 'Emailing sysadmins' ),
	array( ':text' => 'Hunting down, and verbally assaulting, spammers' ),
	array( ':text' => 'Filtering spam' ),
	array( ':text' => 'Ignorning trolls' ),
	array( ':text' => 'Predicting the future' ),
	array( ':text' => 'Editing your wiki' ),
	array( ':text' => 'Selecting new server kittens' ),
	array( ':text' => 'Locating the required gigapixels to render' ),
	array( ':text' => 'Spinning up the hamster' ),
	array( ':text' => 'Shovelling coal into the server' ),
	array( ':text' => 'Programming the flux capacitor' ),
	array( ':text' => 'Commencing infinite loop (this may take some time)' ),
	array( ':text' => 'Downloading all of your interwebs' ),
	array( ':text' => 'Searching for the any key' ),
	array( ':text' => 'I\'m not slacking off, my code\'s compiling' ),
);
// List of sites to rotate
$sites = array(
	// WikiSwarm for 00:01:23
	array(
		':name' => 'Video',
		':url' => 'visualizations/video',
		':time' => 83000,
		':weight' => 1,
	),
	// XKCD for 00:02:00
	array(
		':name' => 'XKCD',
		':url' => 'visualizations/xkcd',
		':time' => 120000,
		':weight' => 1,
		':run' => false,
	),
	// Anon-edits for 00:10:00
	array(
		':name' => 'WikipediaViz',
		':url' => 'visualizations/anonedits',
		':time' => 360000,
		':weight' => 1,
	),
	// Commons for 00:10:00
	array(
		':name' => 'Commons Files',
		':url' => 'http://toolserver.org/~para/Commons:Special:NewFiles',
		':time' => 360000,
		':weight' => 1,
	),
	// Wikimedia Project Growth for 00:01:15
	array(
		':name' => 'Growth',
		':url' => 'visualizations/growth',
		':time' => 75000,
		':weight' => 1,
	),
);
?>
