$(document).ready( function() {
	function load() {
		// Show overlay and load iframe
		$( '#overlay' )
			.fadeIn( 'slow', function() {
				$.ajax( {
					'url': 'index.php',
					'data': { 'query': $( '#screen' ).attr( 'rel' ) },
					'dataType': 'json',
					'success': function( response ) {
						if ( response.status === 'ok' ) {
							// Allow 30 seconds to load
							var timeout = setTimeout( load, 30000 );
							$( '#screen' )
								.attr( 'src', response.url )
								.load( function() {
									clearTimeout( timeout );
									// Hide overlay
									$( '#overlay' ).fadeOut( 'slow', function() {
										$( '#label' ).text( response.text );
									} );
								} );
							// Load again after a set time
							setTimeout( load, response.time );
						} else {
							// Display error
							$( '#label' ).text( 'Server error (' + response.message + '), trying again in a bit...' );
							// Load again after 10 seconds
							setTimeout( load, 10000 );
						}
					},
					'failure': function() {
						$( '#label' ).text( 'Server not responding, trying again in bit...' );
						// Load again after 10 seconds
						setTimeout( load, 10000 );
					}
				} );
			} );
	}
	// Get the ball rolling
	load();
} );