var express = require( 'express' )
	fs = require( 'fs' )
	app = express.createServer();

var config = JSON.parse( fs.readFileSync( 'config.json' ) );

app
	.configure( function() {
		app
			.use( '/public', express.static( __dirname + '/public' ) )
			.use( '/visualizations', express.static( __dirname + '/visualizations' ) )
			.use( express.errorHandler( { dumpExceptions: true, showStack: true } ) )
			.use( express.bodyParser() )
			.set( 'view engine', 'ejs' );
	} )
	.get( '/', function( req, res ) {
	    res.partial( 'setup' );
	} )
	.get( '/admin', function( req, res ) {
	    res.partial( 'admin', { 'locals': config } );
	} )
	.post( '/admin/sites', function( req, res ) {
		res.contentType( 'json' );
		var data;
		switch ( req.body.action ) {
			case 'run':
				var run = req.body.run === 'true';
				config.sites[req.body.site].run = run;
				console.log( ( run ? 'Enabled' : 'Disabled' ) + ' ' + req.body.site );
				data = { 'status': 'ok',  };
				break;
			default:
				data = { 'status': 'error' };
				break;
		}
		res.send( JSON.stringify( data ) )
	} )
	.get( '/display/random', function( req, res ) {
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
	} )
	.get( '/display/:frameset', function( req, res ) {
	    res.partial( 'frameset', { 'locals': req.params } );
	} )
	.get( '/display/:frameset/:display', function( req, res ) {
	    res.partial( 'display', { 'locals': req.params } );
	} )
	.listen( 8124 );
