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
