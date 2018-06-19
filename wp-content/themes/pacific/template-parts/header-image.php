<?php
/**
 * Header image template part
 *
 * @package Pacific
 */

$term_id = ( is_category() || is_tag() ) ? get_queried_object()->term_id : '';
$image_id = get_term_meta( $term_id, 'image', true );
$page_for_posts = get_option( 'page_for_posts' );
?>

<div class="custom-header-media">
<?php if( is_singular() && has_post_thumbnail() ) :?>
	<div id="wp-custom-header" class="wp-custom-header">
		<?php the_post_thumbnail( 'full' );?>
	</div><!-- #wp-custom-header -->
<?php elseif( !empty( $page_for_posts ) && is_home() ) : ?>
	<div id="wp-custom-header" class="wp-custom-header">
		<?php echo get_the_post_thumbnail( absint( $page_for_posts ), 'full' );?>
	</div><!-- #wp-custom-header -->
<?php elseif( ( is_category() || is_tag() ) && !empty( $image_id ) ) : ?>
	<div id="wp-custom-header" class="wp-custom-header">
		<?php echo wp_get_attachment_image( $image_id, 'full' );?>
	</div><!-- #wp-custom-header -->
<?php else : ?>
	<?php the_custom_header_markup(); ?>
<?php endif;?>
</div><!-- .custom-header-media -->
