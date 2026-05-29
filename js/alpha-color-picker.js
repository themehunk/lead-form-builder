/**
 * Alpha Color Picker — extends wp-color-picker with an opacity/alpha slider.
 * Registers jQuery.fn.alphaColorPicker()
 */
( function( $ ) {
	'use strict';

	$.fn.alphaColorPicker = function( options ) {

		return this.each( function() {
			var $input    = $( this );
			var $wrap     = $input.closest( '.wp-picker-container' );

			// Initialise the standard wp-color-picker first (if not already done)
			if ( ! $input.hasClass( 'wp-color-picker' ) ) {
				$input.wpColorPicker( $.extend( {}, options ) );
			}

			// Wait for wpColorPicker DOM to be built
			var $container = $input.closest( '.wp-picker-container' );
			if ( ! $container.length ) {
				return;
			}

			// Build alpha slider only once
			if ( $container.find( '.lfb-alpha-wrap' ).length ) {
				return;
			}

			var currentVal = $input.val() || '';
			// Extract alpha from 8-digit hex (#RRGGBBAA) or default to 100
			var alpha = 100;
			if ( /^#[0-9a-fA-F]{8}$/.test( currentVal ) ) {
				alpha = Math.round( parseInt( currentVal.slice( -2 ), 16 ) / 255 * 100 );
			}

			var $alphaWrap = $(
				'<div class="lfb-alpha-wrap">' +
					'<label class="lfb-alpha-label">Opacity</label>' +
					'<input type="range" class="lfb-alpha-slider" min="0" max="100" step="1" value="' + alpha + '">' +
					'<span class="lfb-alpha-val">' + alpha + '%</span>' +
				'</div>'
			);

			$container.find( '.wp-picker-holder' ).append( $alphaWrap );

			// Update color value when alpha slider changes
			$alphaWrap.find( '.lfb-alpha-slider' ).on( 'input change', function() {
				var sliderVal = parseInt( $( this ).val(), 10 );
				$alphaWrap.find( '.lfb-alpha-val' ).text( sliderVal + '%' );

				var hex = $input.wpColorPicker( 'color' );
				if ( hex && /^#[0-9a-fA-F]{6}$/.test( hex ) ) {
					var alphaHex = Math.round( sliderVal / 100 * 255 ).toString( 16 ).padStart( 2, '0' );
					$input.val( hex + alphaHex ).trigger( 'change' );
				}

				if ( options && typeof options.change === 'function' ) {
					options.change.call( this, null, { color: { toString: function() { return $input.val(); } } } );
				}
			} );

			// Keep slider in sync when colour wheel changes
			$input.on( 'change', function() {
				var v = $( this ).val();
				if ( /^#[0-9a-fA-F]{8}$/.test( v ) ) {
					var a = Math.round( parseInt( v.slice( -2 ), 16 ) / 255 * 100 );
					$alphaWrap.find( '.lfb-alpha-slider' ).val( a );
					$alphaWrap.find( '.lfb-alpha-val' ).text( a + '%' );
				}
			} );
		} );
	};

} )( jQuery );
