$(document).ready( function() {
	var loadTimeout, reloadTimeout;
	function load() {
		clearTimeout( loadTimeout );
		// Show overlay and load iframe
		$( '.overlay' )
			.fadeIn( 'slow', function() {
				$.ajax( {
					'url': '/display/random?timestamp=' + ( new Date() ).getTime(),
					'data': { 'query': $( '.screen' ).attr( 'rel' ) },
					'dataType': 'json',
					'success': function( response ) {
						if ( response.status === 'ok' ) {
							// Allow 30 seconds to load
							loadTimeout = setTimeout( load, 30000 );
							$( '.screen' )
								.attr( 'src', response.url )
								.load( function() {
									clearTimeout( loadTimeout );
									// Hide overlay
									$( '.overlay' ).fadeOut( 'slow' );
									$( '.overlay-label' ).text( '' );
								} );
							// Load again after a set time
							clearTimeout( reloadTimeout );
							reloadTimeout = setTimeout( load, response.time );
						} else {
							// Display error
							$( '.overlay-label' ).text(
								'Server error (' + response.message + '), trying again in a bit...'
							);
							// Load again after 10 seconds
							setTimeout( load, 10000 );
						}
					},
					'failure': function() {
						$( '.overlay-label' ).text( 'Server not responding, trying again in bit...' );
						// Load again after 10 seconds
						clearTimeout( reloadTimeout );
						reloadTimeout = setTimeout( load, 10000 );
					}
				} );
			} );
	}
	// Get the ball rolling
	load();
} );
