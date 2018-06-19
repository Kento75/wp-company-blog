<?php
/**
 * Pacific Theme Customizer Output
 *
 * @package Pacific
 */

/**
 * Print inline style
 *
 * @return string
 */
function pacific_add_inline_style(){

	$setting 			= pacific_setting_default();

	$css_selector 		= pacific_css_color_selector();

	$primary_color 		= get_theme_mod( 'primary_color', $setting['primary_color'] );
	$secondary_color 	= get_theme_mod( 'secondary_color', $setting['secondary_color'] );

	$css= '';

	if ( get_theme_mod( 'post_date', $setting['post_date'] ) == false ) {
		$css .= '.entry-meta .posted-on{ display: none }';
	}

	if ( get_theme_mod( 'post_author', $setting['post_author'] ) == false ) {
		$css .= '.entry-meta .byline{ display: none }';
	}

	if ( get_theme_mod( 'post_cat', $setting['post_cat'] ) == false ) {
		$css .= '.entry-footer .cat-links{ display: none }';
	}

	if ( get_theme_mod( 'post_tag', $setting['post_tag'] ) == false ) {
		$css .= '.entry-footer .tags-links{ display: none }';
	}

	if ( get_theme_mod( 'post_comments', $setting['post_comments'] ) == false ) {
		$css .= '.entry-footer .comments-link{ display: none }';
	}

	if ( ( get_theme_mod( 'post_cat', $setting['post_cat'] ) && get_theme_mod( 'post_tag', $setting['post_tag'] ) && get_theme_mod( 'post_comments', $setting['post_comments'] ) ) == false ) {
		$css .= '.masonry-container .entry-footer { display: none }';
	}

	if ( $primary_color ) {
		$css .= sprintf( '%s{ background-color: %s }', $css_selector['primary_color_background'], esc_attr( $primary_color ) );
		$css .= sprintf( '%s{ border-color: %s }', $css_selector['primary_color_border'], esc_attr( $primary_color ) );
		$css .= sprintf( '%s{ color: %s }', $css_selector['primary_color_text'], esc_attr( $primary_color ) );
		$css .= sprintf( '::selection{background-color:%1$s}::-moz-selection{background-color:%1$s}', esc_attr( $primary_color ) );
	}

	if ( $secondary_color ) {
		$css .= sprintf( '%s{ background-color: %s }', $css_selector['secondary_color_background'], esc_attr( $secondary_color ) );
		$css .= sprintf( '%s{ color: %s }', $css_selector['secondary_color_text'], esc_attr( $secondary_color ) );
	}

    $css = str_replace( array( "\n", "\t", "\r" ), '', $css );

	if ( ! empty( $css ) ) {
		wp_add_inline_style( 'pacific-style', apply_filters( 'pacific_inline_style', trim( $css ) ) );
	}

}
add_action( 'wp_enqueue_scripts', 'pacific_add_inline_style' );

/**
 * [pacific_customizer_style_placeholder description]
 * @return [type] [description]
 */
function pacific_customizer_style_placeholder(){
	if ( is_customize_preview() ) {
		echo '<style id="primary-color"></style>';
		echo '<style id="secondary-color"></style>';
	}
}
add_action( 'wp_head', 'pacific_customizer_style_placeholder', 15 );

/**
 * [pacific_editor_style description]
 * @param  [type] $mceInit [description]
 * @return [type]          [description]
 */
function pacific_editor_style( $mceInit ) {

	$primary_color 			= get_theme_mod( 'primary_color', '#ff9800' );
	$secondary_color 		= get_theme_mod( 'secondary_color', '#ef6c00' );

	$styles = '';
	$styles .= '.mce-content-body a{ color: ' . esc_attr( $primary_color ) . '; }';
	$styles .= '.mce-content-body a:hover, .mce-content-body a:focus{ color: ' . esc_attr( $secondary_color ) . '; }';
	$styles .= '.mce-content-body ::selection{ background-color: ' . esc_attr( $secondary_color ) . '; }';
	$styles .= '.mce-content-body ::-mozselection{ background-color: ' . esc_attr( $secondary_color ) . '; }';

	$styles = str_replace( array( "\n", "\t", "\r" ), '', $styles );

	if ( !isset( $mceInit['content_style'] ) ) {
		$mceInit['content_style'] = trim( $styles ) . ' ';
	} else {
		$mceInit['content_style'] .= ' ' . trim( $styles ) . ' ';
	}

	return $mceInit;

}
add_filter( 'tiny_mce_before_init', 'pacific_editor_style' );
