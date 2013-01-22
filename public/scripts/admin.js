var lpa = {
	'sites': function() {
		$.ajax( {
			'type': 'GET',
			'url': '/admin/sites',
			'dataType': 'json',
			'context': $( '#sites' ),
			'success': function( response ) {
				$table = $(this).find( 'tbody' );
				$.each( response, function( key, site ) {
					$table.append(
						$( '<tr></tr>' )
							.append(
								$( '<td></td>' )
									.append(
										$( '<input type="checkbox" class="sites-run-input">' )
											.attr( {
												'id': 'sites-run-' + key,
												'rel': key,
												'checked': !!site.run
											} )
									)
									.append(
										$( '<label>Run</label>' )
											.attr( 'for', 'sites-run-' + key )
									)
							)
							.append( $( '<td></td>' ).text( key ) )
							.append(
								$( '<td></td>' )
									.append(
										$( '<a target="_blank"></a>' )
											.attr( 'href', site.url )
											.text( site.url )
									)
							)
							.append( $( '<td></td>' ).text( site.display || '-' ) )
							.append(
								$( '<td class="numeric"></td>' )
									.append(
										$( '<input type="text" class="sites-time-input">' )
											.attr( {
												'id': 'sites-time-' + key,
												'rel': key,
												'value': Math.round( site.time / 1000 )
											} )
									)
									.append( 'sec' )
							)
					);
				} );
				$(this).find( 'input:text' )
					.each( function() {
						var $this = $(this),
							value = $this.val(),
							syncTimeout;
						$this.bind( 'keyup click cut paste', function() {
							if ( $this.val() !== value ) {
								$this.addClass( 'changed' );
								clearTimeout( syncTimeout );
								syncTimeout = setTimeout( function() {
									value = $this.val();
									$.ajax( {
										'type': 'POST',
										'url': '/admin/sites',
										'data': {
											'action': 'time',
											'time': value,
											'site': $this.attr( 'rel' )
										},
										'dataType': 'json',
										'context': $(this),
										'success': function( response ) {
											$this.removeClass( 'changed' );
										}
									} );
								}, 1000 );
							}
						} );
					} );
				$(this).find( 'input:checkbox' )
					.each( function() {
						$(this).button( {
							'text': false,
							'icons': {
								'primary': (
									$(this).is( ':checked' ) ? 'ui-icon-check' : 'ui-icon-cancel'
								)
							}
						} );
					} )
					.click( function() {
						$(this).button( {
							'text': false,
							'icons': { 'primary': 'ui-icon-clock' },
							'disabled': true
						} );
						var value = $(this).is( ':checked' );
						$.ajax( {
							'type': 'POST',
							'url': '/admin/sites',
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
									'icons': {
										'primary': ( value ? 'ui-icon-check' : 'ui-icon-cancel' )
									},
									'disabled': false
								} );
							}
						} );
					} );
			}
		} );
	}
};

lpa.sites();

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
} );