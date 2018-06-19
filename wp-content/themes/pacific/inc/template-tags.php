<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Pacific
 */

if ( ! function_exists( 'pacific_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function pacific_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'pacific' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'pacific' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);

}
endif;

if ( ! function_exists( 'pacific_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function pacific_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'pacific' ) ); // WPCS: XSS OK.
		if ( $categories_list ) {
			/* translators: 1: list of categories. */
			printf( '<span class="cat-links">%1$s <span class="screen-reader-text">%2$s</span> %3$s</span>', pacific_get_svg( array( 'icon' => 'category' ) ), esc_html__( 'Posted in', 'pacific' ), $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'pacific' ) ); // WPCS: XSS OK.
		if ( $tags_list ) {
			/* translators: 1: list of tags. */
			printf( '<span class="tags-links">%1$s <span class="screen-reader-text">%2$s</span> %3$s</span>', pacific_get_svg( array( 'icon' => 'tag' ) ), esc_html__( 'Tagged', 'pacific' ), $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		echo pacific_get_svg( array( 'icon' => 'comment' ) ); // WPCS: XSS OK.
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'pacific' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}

}
endif;

if ( ! function_exists( 'pacific_breadcrumb' ) ) :
/**
 * Display breadcrumb compatibility
 * @return void
 */
function pacific_breadcrumb(){

	$breadcrumb_markup_open = '<div id="breadcrumb" typeof="BreadcrumbList" vocab="http://schema.org/"><div class="wrap">';
	$breadcrumb_markup_close = '</div></div>';

	if ( function_exists( 'bcn_display' ) ) {
		echo $breadcrumb_markup_open; // WPCS: XSS OK.
		bcn_display();
		echo $breadcrumb_markup_close; // WPCS: XSS OK.
	}
	elseif ( function_exists( 'breadcrumbs' ) ) {
		breadcrumbs();
	}
	elseif ( function_exists( 'crumbs' ) ) {
		crumbs();
	}
	elseif ( class_exists( 'WPSEO_Breadcrumbs' ) ) {
		yoast_breadcrumb( $breadcrumb_markup_open, $breadcrumb_markup_close );
	}
	elseif( function_exists( 'yoast_breadcrumb' ) && ! class_exists( 'WPSEO_Breadcrumbs' ) ) {
		yoast_breadcrumb( $breadcrumb_markup_open, $breadcrumb_markup_close );
	}

}
endif;

if ( !function_exists( 'pacific_posts_navigation' ) ) :
/**
 * Post Navigation/Pagination
 *
 * @return void
 */
function pacific_posts_navigation(){

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
		return;
	}

	if ( get_theme_mod( 'posts_navigation', 'posts_navigation' ) == 'posts_navigation' ) {
		the_posts_navigation( array(
            'prev_text'          => esc_html__( '&larr; Older posts', 'pacific' ),
            'next_text'          => esc_html__( 'Newer posts &rarr;', 'pacific' ),
		) );
	} else {
		the_posts_pagination( array(
			'prev_text'          => sprintf( '%s <span class="screen-reader-text">%s</span>', pacific_get_svg( array( 'icon' => 'previous' ) ), esc_html__( 'Previous Page', 'pacific' ) ), // WPCS: XSS OK.
			'next_text'          => sprintf( '%s <span class="screen-reader-text">%s</span>', pacific_get_svg( array( 'icon' => 'next' ) ), esc_html__( 'Next Page', 'pacific' ) ), // WPCS: XSS OK.
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'pacific' ) . ' </span>',
		) );
	}

}
endif;

if( ! function_exists( 'pacific_footer_copyright' ) ) :
/**
 * Footer copyright default.
 *
 * @return string
 */
function pacific_get_footer_copyright(){

	// Translators: %1$s: Dynamic year, %2$s: Site link, %3$s: WordPress link
	$default_footer_copyright =	sprintf( __( 'Copyright &copy; %1$s %2$s. Proudly powered by %3$s.', 'pacific' ),
		date_i18n( __( 'Y', 'pacific' ) ),
		'<a href="'. esc_url( home_url('/') ) .'">'. esc_html( get_bloginfo( 'name' ) ) .'</a>',
		'<a href="'. esc_url( __( 'https://wordpress.org/', 'pacific' ) ) .'">'. __( 'WordPress', 'pacific' ) .'</a>' );

	apply_filters( 'pacific_footer_copyright', $default_footer_copyright );

	$footer_copyright = get_theme_mod( 'footer_copyright', $default_footer_copyright );

	if ( ! empty( $footer_copyright ) ) {
		$footer_copyright = str_replace( '[YEAR]', date_i18n( __( 'Y', 'pacific' ) ), $footer_copyright );
		$footer_copyright = str_replace( '[SITE]', '<a href="'. esc_url( home_url('/') ) .'">'. esc_html( get_bloginfo( 'name' ) ) .'</a>', $footer_copyright );
		return wp_kses_post( $footer_copyright );
	} else {
		return wp_kses_post( $default_footer_copyright );
	}

}
endif;

if( ! function_exists( 'pacific_do_footer_copyright' ) ) :
/**
 * Footer copyright.
 *
 * @return string
 */
function pacific_do_footer_copyright(){

	echo '<div class="site-info">'. wp_kses_post( pacific_get_footer_copyright() ) . '</div>';
	if ( get_theme_mod( 'theme_designer', true ) ) {
		echo '<div class="site-designer">';
		// Translators: %1$s: Theme designer logo, %2$s: Theme designer site link
		echo sprintf( esc_html__( 'Theme design by %1$s %2$s.', 'pacific' ), pacific_get_svg( array( 'icon' => 'campaignkit' ) ), '<a href="'. esc_url( 'https://campaignkit.co/' ) .'">Campaign Kit</a>' ); // WPCS: XSS OK.
		echo '</div>';
	}

}
endif;

if ( ! function_exists( 'pacific_return_to_top' ) ) :
/**
 * Return to top of page button.
 *
 * @return string
 */
function pacific_return_to_top(){
	if( get_theme_mod( 'return_top', true ) ) {
		echo '<a href="#page" class="return-to-top">';
			echo '<span class="screen-reader-text">'. esc_html__( 'Return to top of page', 'pacific' ) .'</span>';
			echo pacific_get_svg( array( 'icon' => 'top' ) ); // WPCS: XSS OK.
		echo '</a>';
	}
}
endif;
