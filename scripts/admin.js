$(document).ready( function() {
	$( '#tabs' ).tabs();
	$( '#tabs li a' ).click( function() {
		$(this).blur();
	} );
	$( '#displays a.blank' ).click( function( e ) {
		$(this).blur();
		e.preventDefault();
		return false;
	} );
	$( '#menu button' ).button();
	$( '#sites input[type=checkbox]' )
		.each ( function() {
			$(this).button( {
				'text': false,
				'icons': { 'primary': ( $(this).is( ':checked' ) ? 'ui-icon-check' : 'ui-icon-cancel' ) }
			} );
		} )
		.click( function() {
			$(this).button( { 'text': false, 'icons': { 'primary': 'ui-icon-clock' }, 'disabled': true } );
			var value = $(this).is( ':checked' ) + 0;
			$.ajax( {
				'type': 'POST',
				'url': 'admin/index.php',
				'data': {
					'action': 'run',
					'run': value,
					'site': $(this).attr( 'rel' )
				},
				'dataType': 'json',
				'context': $(this),
				'success': function( response ) {
					$(this).button( {
						'text': false,
						'icons': { 'primary': ( value ? 'ui-icon-check' : 'ui-icon-cancel' ) },
						'disabled': false
					} );
				}
			} );
		} );
} );