/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

/* global PacificCustomizerl10n */
( function( $, api ) {

	// Site title and description.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	api( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Post Meta
	api( 'post_date', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-meta .posted-on' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-meta .posted-on' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_author', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-meta .byline' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-meta .byline' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_cat', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .cat-links' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .cat-links' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_tag', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .tags-links' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .tags-links' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'post_comments', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.entry-footer .comments-link' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.entry-footer .comments-link' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'author_display', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.author-info' ).css( {
					'display': 'inline-block'
				} );
			} else {
				$( '.author-info' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'theme_designer', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.site-designer' ).css( {
					'display': 'block'
				} );
			} else {
				$( '.site-designer' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'return_top', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( '.return-to-top' ).css( {
					'display': 'block'
				} );
			} else {
				$( '.return-to-top' ).css( {
					'display': 'none'
				} );
			}
		} );
	} );

	api( 'primary_color', function( value ){
		value.bind( function( to ) {
			$( '#primary-color' ).text(
				PacificCustomizerl10n.primary_color_background + '{background-color:'+ to +'}' +
				PacificCustomizerl10n.primary_color_border + '{border-color:'+ to +'}' +
				PacificCustomizerl10n.primary_color_text + '{color:'+ to +'}' );
		} );
	} );

	api( 'secondary_color', function( value ){
		value.bind( function( to ) {
			$( '#secondary-color' ).text(
				PacificCustomizerl10n.secondary_color_background + '{background-color:'+ to +'}' +
				PacificCustomizerl10n.secondary_color_text + '{color:'+ to +'}' );
		} );
	} );

} )( jQuery, wp.customize );
