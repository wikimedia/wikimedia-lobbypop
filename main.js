var express = require( 'express' ),
	fs = require( 'fs' ),
	app = express();

var config = JSON.parse( fs.readFileSync( 'config.json' ) );

app.configure( function() {
	app
		.use( '/public', express.static( __dirname + '/public' ) )
		.use( '/visualizations', express.static( __dirname + '/visualizations' ) )
		.use( express.errorHandler( { dumpExceptions: true, showStack: true } ) )
		.use( express.bodyParser() )
		.set( 'view engine', 'ejs' );
} );
app.get( '/', function( req, res ) {
    res.render( 'setup' );
} );
app.get( '/admin', function( req, res ) {
    res.render( 'admin', { 'locals': config } );
} );
app.get( '/admin/sites', function( req, res ) {
	res.send( JSON.stringify( config.sites ) );
} );
app.post( '/admin/sites', function( req, res ) {
	res.contentType( 'json' );
	var data;
	switch ( req.body.action ) {
		case 'run':
			var run = req.body.run === 'true';
			config.sites[req.body.site].run = run;
			console.log( ( run ? 'Enabled' : 'Disabled' ) + ' ' + req.body.site );
			data = { 'status': 'ok' };
			break;
		case 'time':
			var time = Number( req.body.time );
			config.sites[req.body.site].time = time * 1000;
			console.log( 'Set ' + req.body.site + ' to run for ' + time + 'sec' );
			data = { 'status': 'ok' };
			break;
		default:
			data = { 'status': 'error' };
			break;
	}
	res.send( JSON.stringify( data ) );
} );
app.get( '/display/random', function( req, res ) {
	res.contentType( 'json' );
	var siteArray = [];
	for ( var name in config.sites ) {
		if ( config.sites[name].run ) {
			siteArray.push( config.sites[name] );
		}
	}
	var site = siteArray[Math.round( Math.random() * ( siteArray.length - 1 ) )];
	var text = config.messages[Math.round( Math.random() * ( config.messages.length - 1 ) )];
	res.send( JSON.stringify( {
		'status': 'ok',
		'url': site.url,
		'time': site.time,
		'text': text
	} ) );
} );
app.get( '/display/:frameset', function( req, res ) {
    res.render( 'frameset', { 'locals': req.params } );
} );
app.get( '/display/:frameset/:display', function( req, res ) {
    res.render( 'display', { 'locals': req.params } );
} );
app.listen( 8124 );
