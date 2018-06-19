<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Pacific
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function pacific_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_customize_preview() ) {
		$classes[] = 'pacific-customizer';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	if( is_singular() && !is_front_page() ) {
		$classes[] = 'single-content';
	}

	return $classes;
}
add_filter( 'body_class', 'pacific_body_classes' );

/**
 * Removes hentry class from the array of post classes.
 * Currently, having the class on pages is not correct use of hentry.
 * hentry requires more properties than pages typically have.
 * Core is not likely to remove class because of backward compatibility.
 * See: https://core.trac.wordpress.org/ticket/28482
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function pacific_post_classes( $classes ) {
	if ( 'page' === get_post_type() ) {
		$classes = array_diff( $classes, array( 'hentry' ) );
	}
	$classes[] = 'entry';
	return $classes;
}
add_filter( 'post_class', 'pacific_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function pacific_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'pacific_pingback_header' );

if ( ! function_exists( 'pacific_add_filters' ) ) :
/**
 * Hook filters to the front-end only.
 */
function pacific_add_filters() {
	if ( is_page_template( 'page-templates/front-page.php' ) || is_home() || is_category() || is_tag() || is_author() || is_date() || is_search() ) {
		add_filter( 'the_title', 'pacific_untitled_post' );
		add_filter( 'excerpt_length', 'pacific_excerpt_length', 999 );
		add_filter( 'excerpt_more', 'pacific_excerpt_more' );
		add_filter( 'the_content_more_link', 'pacific_excerpt_more', 10, 2 );
		add_filter( 'embed_defaults', 'pacific_default_embed_size' );
		add_filter( 'embed_oembed_html', 'pacific_mixcloud_oembed_parameter', 10, 3 );
	}
	if ( is_singular() ) {
		add_filter( 'the_title', 'pacific_untitled_post' );
		add_filter( 'embed_defaults', 'pacific_default_embed_size' );
		add_filter( 'embed_oembed_html', 'pacific_mixcloud_oembed_parameter', 10, 3 );
		add_filter( 'comment_form_defaults', 'pacific_comment_form', 10, 5 );
	}
}
endif;
add_action( 'wp', 'pacific_add_filters' );

/**
 * Add (Untitled) for post who doesn't have title
 * @param  string  $title
 * @return string
 */
function pacific_untitled_post( $title ) {

	// Translators: Used as a placeholder for untitled posts on non-singular views.
	if ( ! $title && ! is_singular() && in_the_loop() && ! is_admin() )
		$title = esc_html__( '(Untitled)', 'pacific' );

	return $title;
}
add_filter( 'the_title', 'pacific_untitled_post' );

/**
 * Filter the except length to 20 characters.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function pacific_excerpt_length( $length ) {
	if ( get_theme_mod( 'excerpt_length', 20 ) !== '' ) {
		return (int)get_theme_mod( 'excerpt_length', 20 );
	} else {
		return 20;
	}
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function pacific_excerpt_more() {
	$link = sprintf( '<a href="%1$s" class="more-link">%2$s</a>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Name of current post */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span> &rarr;', 'pacific' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; <div class="more-link-wrap">'. $link .'</div>';
}

/**
 * [pacific_comment_form description]
 * @param  [type] $fields [description]
 * @return [type]         [description]
 */
function pacific_comment_form( $fields ) {

	$fields['cancel_reply_link'] = sprintf( '%s <span class="screen-reader-text">%s</span>',
		pacific_get_svg( array( 'icon' => 'close' ) ),
		__( 'Cancel reply', 'pacific' ) );

	return $fields;
}

/**
 * Fix embed height
 * @return [type] [description]
 */
function pacific_default_embed_size(){
	return array( 'width' => 780, 'height' => 180 );
}

/**
 * [pacific_mixcloud_oembed_parameter description]
 * @param  [type] $html [description]
 * @param  [type] $url  [description]
 * @param  [type] $args [description]
 * @return [type]       [description]
 */
function pacific_mixcloud_oembed_parameter( $html, $url, $args ) {
	return str_replace( 'hide_cover=1', 'hide_cover=1&hide_tracklist=1', $html );
}
