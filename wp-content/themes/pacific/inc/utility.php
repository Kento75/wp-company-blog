<?php
/**
 * Helper functions that act independently of the theme templates.
 *
 * @package Pacific
 */

if ( ! function_exists( 'pacific_is_sticky' ) ) :
/**
 * [pacific_is_sticky description]
 * @return bool
 */
function pacific_is_sticky(){
	return (bool) is_sticky() && !is_paged() && !is_singular() && !is_archive();
}
endif;
