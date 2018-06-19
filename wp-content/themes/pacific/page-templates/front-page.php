<?php
/**
 * Template Name: Front Page
 *
 * @package Pacific
 */
get_header();

$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$wp_query = new WP_Query( array(
	'post_type' 		=> 'post',
	'posts_per_page' 	=> absint( get_option( 'posts_per_page' ) ),
	'paged'				=> $paged
) );
?>

<div class="content-sidebar-wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main masonry-container">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/*
				 * Include the Post-Format-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_format() );

			endwhile;

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
		<?php pacific_posts_navigation();?>
	</div><!-- #primary -->
</div><!-- .content-sidebar-wrap -->
<?php
get_footer();
