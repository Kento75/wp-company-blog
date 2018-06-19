<?php
/**
 * Template part for displaying content hero
 *
 * @package Pacific
 */
$has_post_thumbnail = ( is_singular() && has_post_thumbnail( get_the_id() ) || is_archive() ) ? ' has-post-thumbnail' : '';
$page_for_posts = get_option( 'page_for_posts' );
?>

<div id="section-hero" class="section-hero<?php echo esc_attr( $has_post_thumbnail );?>">

	<?php get_template_part( 'template-parts/header', 'image' );?>

	<div class="hero-content">
		<div class="wrap">
			<?php if ( is_page_template( 'page-templates/front-page.php' ) ) :?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					<div class="entry-hero">
						<?php the_content();?>
					</div><!-- .entry-hero -->
				<?php endwhile;?>

			<?php elseif ( !empty( $page_for_posts ) && is_home() ) : ?>

				<h1 class="entry-title">
					<?php echo get_the_title( absint( $page_for_posts ) );?>
				</h1>
				<div class="entry-hero">
					<?php echo wp_kses_post( wpautop( get_post_field( 'post_content', absint( $page_for_posts ) ) ) );?>
				</div>

			<?php elseif ( is_archive() ) : ?>

				<?php the_archive_title( '<h1 class="entry-title">', '</h1>' );?>
				<?php the_archive_description( '<div class="archive-description">', '</div>' );?>

			<?php elseif ( is_search() ) : ?>

				<h1 class="entry-title">
					<?php
					// Translators: %s: Search query
					printf( esc_html__( 'Search Results for: %s', 'pacific' ), '<span>' . get_search_query() . '</span>' );?>
				</h1>

			<?php elseif ( is_singular() ) : ?>

				<?php the_title( '<p class="entry-title">', '</p>' ); ?>

			<?php endif;?>
		</div><!-- .wrap -->
	</div><!-- .hero-content -->

</div><!-- .section-hero -->
