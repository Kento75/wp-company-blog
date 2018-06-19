/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var isIe = /(trident|msie)/i.test( navigator.userAgent );

	if ( isIe && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
} )();
/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
	var container, button, menu, links, i, len;

	container = document.getElementById( 'site-navigation' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );
	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};

	// Get all the link elements within the menu.
	links    = menu.getElementsByTagName( 'a' );

	// Each time a menu link is focused or blurred, toggle focus.
	for ( i = 0, len = links.length; i < len; i++ ) {
		links[i].addEventListener( 'focus', toggleFocus, true );
		links[i].addEventListener( 'blur', toggleFocus, true );
	}

	/**
	 * Sets or removes .focus class on an element.
	 */
	function toggleFocus() {
		var self = this;

		// Move up through the ancestors of the current link until we hit .nav-menu.
		while ( -1 === self.className.indexOf( 'nav-menu' ) ) {

			// On li elements toggle the class .focus.
			if ( 'li' === self.tagName.toLowerCase() ) {
				if ( -1 !== self.className.indexOf( 'focus' ) ) {
					self.className = self.className.replace( ' focus', '' );
				} else {
					self.className += ' focus';
				}
			}

			self = self.parentElement;
		}
	}

	/**
	 * Toggles `focus` class to allow submenu access on tablets.
	 */
	( function( container ) {
		var touchStartFn, i,
			parentLink = container.querySelectorAll( '.menu-item-has-children > a, .page_item_has_children > a' );

		if ( 'ontouchstart' in window ) {
			touchStartFn = function( e ) {
				var menuItem = this.parentNode, i;

				if ( ! menuItem.classList.contains( 'focus' ) ) {
					e.preventDefault();
					for ( i = 0; i < menuItem.parentNode.children.length; ++i ) {
						if ( menuItem === menuItem.parentNode.children[i] ) {
							continue;
						}
						menuItem.parentNode.children[i].classList.remove( 'focus' );
					}
					menuItem.classList.add( 'focus' );
				} else {
					menuItem.classList.remove( 'focus' );
				}
			};

			for ( i = 0; i < parentLink.length; ++i ) {
				parentLink[i].addEventListener( 'touchstart', touchStartFn, false );
			}
		}
	}( container ) );
} )();
(function ($) {

	$.fn.parallax = function () {
		var window_width = $(window).width();
		// Parallax Scripts
		return this.each(function() {
			var $this = $(this);
			$this.addClass('parallax');

			function updateParallax(initial) {
				var container_height;
				if (window_width < 601) {
					container_height = ($this.height() > 0) ? $this.height() : $this.children('img').height();
				}
				else {
					container_height = ($this.height() > 0) ? $this.height() : 500;
				}
				var $img = $this.children('img').first();
				var img_height = $img.height();
				var parallax_dist = img_height - container_height;
				var bottom = $this.offset().top + container_height;
				var top = $this.offset().top;
				var scrollTop = $(window).scrollTop();
				var windowHeight = window.innerHeight;
				var windowBottom = scrollTop + windowHeight;
				var percentScrolled = (windowBottom - top) / (container_height + windowHeight);
				var parallax = Math.round((parallax_dist * percentScrolled));

				if (initial) {
					$img.css('display', 'block');
				}
				if ((bottom > scrollTop) && (top < (scrollTop + windowHeight))) {
					$img.css('transform', 'translate3D(-50%,' + parallax + 'px, 0)');
				}

			}

			// Wait for image load
			$this.children('img').one('load', function() {
				updateParallax(true);
			}).each(function() {
				if (this.complete) {
					$(this).trigger('load');
				}
			});

			$(window).scroll(function() {
				window_width = $(window).width();
				updateParallax(false);
			});

			$(window).resize(function() {
				window_width = $(window).width();
				updateParallax(false);
			});

		});

	};
}( jQuery ));
/* global Pacificl10n */
( function( $ ) {

	var pacific = pacific || {};

	pacific.init = function() {

		pacific.$body 		= $( document.body );
		pacific.$window 	= $( window );
		pacific.$html 		= $( 'html' ),
		pacific.$masonry 	= $( '.masonry-container' ),
		pacific.$footerWidgets = $( '#secondary .footer-widgets' );

		this.inlineSVG();
		this.fitVids();
		this.smoothScroll();
		this.stickyHeader();
		this.parallax();
		this.subMenuToggle();
		this.masonry();
		this.postNavHeight();
		this.returnTop();
		this.event();

	};

	pacific.supportsInlineSVG = function() {

		var div = document.createElement( 'div' );
		div.innerHTML = '<svg/>';
		return 'http://www.w3.org/2000/svg' === ( 'undefined' !== typeof SVGRect && div.firstChild && div.firstChild.namespaceURI );

	};

	pacific.inlineSVG = function() {

		if ( true === pacific.supportsInlineSVG() ) {
			document.documentElement.className = document.documentElement.className.replace( /(\s*)no-svg(\s*)/, '$1svg$2' );
		}

	};

	pacific.fitVids = function() {

		$( '#page' ).fitVids({
			customSelector: 'iframe[src^="https://videopress.com"]'
		});

	};

	pacific.smoothScroll = function() {

		var $smoothScroll = $( 'a[href*="#content"], a[href*="#site-navigation"], a[href*="#secondary"], a[href*="#tertiary"], a[href*="#page"]' );

		$smoothScroll.click(function(event) {
	        // On-page links
	        if (
	            location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
	            location.hostname === this.hostname
	        ) {
	            // Figure out element to scroll to
	            var target = $(this.hash);
	            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	            // Does a scroll target exist?
	            if (target.length) {
	                // Only prevent default if animation is actually gonna happen
	                event.preventDefault();
	                $('html, body').animate({
	                    scrollTop: target.offset().top
	                }, 500, function() {
	                    // Callback after animation
	                    // Must change focus!
	                    var $target = $(target);
	                    $target.focus();
	                    if ($target.is(':focus')) { // Checking if the target was focused
	                        return false;
	                    } else {
	                        $target.attr( 'tabindex', '-1' ); // Adding tabindex for elements not focusable
	                        $target.focus(); // Set focus again
	                    }
	                });
	            }
	        }
		});

	};

	pacific.stickyHeader = function() {

		$( '.site-header' ).stickit({
			screenMinWidth: 782,
			zIndex: 5
		});

	};

	pacific.parallax = function() {

		$( '.wp-custom-header' ).parallax();

	};

	pacific.subMenuToggle = function() {

		$( '.main-navigation .sub-menu' ).before( '<button class="sub-menu-toggle" role="button" aria-expanded="false">' + Pacificl10n.expandMenu + Pacificl10n.collapseMenu + Pacificl10n.subNav + '</button>' );
		$( '.sub-menu-toggle' ).on( 'click', function( e ) {

			e.preventDefault();

			var $this = $( this );

			$this.attr( 'aria-expanded', function( index, value ) {
				return 'false' === value ? 'true' : 'false';
			});

			// Add class to toggled menu
			$this.toggleClass( 'toggled' );
			$this.next( '.sub-menu' ).slideToggle( 0 );

		});

	};

	pacific.masonry = function() {

		pacific.$masonry.masonry({
			itemSelector: '.entry',
			columnWidth: '.entry'
		});

		pacific.$footerWidgets.masonry({
			itemSelector: '.widget',
			columnWidth: '.widget'
		});

	};

	pacific.postNavHeight = function() {

        var highestBox = 0,
        	$navLinks = $( '.post-navigation .nav-links' );

        $navLinks.find( 'div a' ).each(function(){
            $navLinks.css('height','auto');
            if($navLinks.height() > highestBox){
                highestBox = $(this).height();
            }
        });

        $navLinks.find( 'div a' ).height(highestBox);

	};

	pacific.returnTop = function() {

		var $returnTop	= $('.return-to-top');

		pacific.$window.scroll(function () {
		    if ($(this).scrollTop() > 500) {
		        $returnTop.removeClass('off').addClass('on');
		    }
		    else {
		        $returnTop.removeClass('on').addClass('off');
		    }
		});

	};

	pacific.event = function() {

		pacific.$window.on('resize', function(){
			pacific.parallax();
		});

		pacific.$window.load(function(){
			pacific.$masonry.masonry( 'reloadItems' );
			pacific.$masonry.masonry( 'layout' );
		});

		pacific.$body.on( 'wp-custom-header-video-loaded', function() {
			pacific.$body.addClass( 'has-header-video' );
		});

		pacific.$body.on( 'post-load', function () {
			pacific.$masonry.masonry( 'reloadItems' );
			pacific.$masonry.masonry( 'layout' );
			pacific.fitVids();
		});

	};

	/** Initialize pacific.init() */
	$( function() {

		pacific.init();

	    if ( 'undefined' === typeof wp || ! wp.customize || ! wp.customize.selectiveRefresh ) {
	        return;
	    }

	    wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {

	    	if ( placement.partial.id ) {
	    		pacific.subMenuToggle();
	    	}

	    });

		wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {

			if ( 'sidebar-1' === sidebarPartial.sidebarId ) {
	            pacific.$footerWidgets.masonry( 'reloadItems' );
	            pacific.$footerWidgets.masonry( 'layout' );
			}

		});

	});

} )( jQuery );
