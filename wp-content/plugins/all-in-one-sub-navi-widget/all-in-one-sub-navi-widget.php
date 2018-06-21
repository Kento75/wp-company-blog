<?php
/*
Plugin Name: All in One Sub Navi Widget
Plugin URI: http://www.warna.info/archives/953/
Description: Sub Navigation Widget. Display sub navigation auto matically on Page, Post and other WordPress pages. (Curren version japanese only.)
Author: jim912
Version: 1.0.2
License: GPLv2 or later
Author URI: http://www.warna.info/
*/


class sub_navi_widget extends WP_Widget {
	
	function sub_navi_widget () {
		$widget_ops = array(
			'classname' => 'sub_navi-widget',
			'description' => 'ホーム、投稿、固定ページでのサブナビをオールインワンで実現'
		);

		$this->defaults = array(
			'home_display'				=> 'latest',
			'home_title'				=> '',
			'home_disp_nums'			=> 5,
			'home_post_type'			=> array( 'post' ),
			
			'post_display'				=> 'm_archives',
			'post_title'				=> '',
			'post_disp_nums'			=> 5,
			'cat_orderby'				=> 'name',
			'cat_order'					=> 'asc',

			'page_display'				=> 'root',
			'page_display_child_of'		=> 1,
			'page_exclude_other_child'	=> 1,
			'page_title'				=> '',
			'page_disp_level'			=> 2,
			'page_exclude_tree'			=> '',
		);

		$this->WP_Widget( 'sub_navi', 'サブナビ', $widget_ops, $widget_ops );
	}

	function widget( $args, $instance ) {
		global $post;
		
		$instance = wp_parse_args( (array)$instance, $this->defaults );

//		var_dump( $args, $instance );
		$output = '';
		if ( is_home() ) {
			switch ( $instance['home_display'] ) {
			case 'latest' :
				$orderby = 'date';
				$widget_title = '最新情報';
				break;
			case 'modify' :
				$orderby = 'modified';
				$widget_title = '更新情報';
				break;
			default :
				return;
			}
			if ( $instance['home_title'] ) {
				$widget_title = $instance['home_title'];
			}
			$limit = absint( $instance['home_disp_nums'] ) ? absint( $instance['home_disp_nums'] ) : $this->defaults['home_disp_nums'];
			$home_posts = get_posts( array( 'orderby' => $orderby, 'post_type' => $instance['home_post_type'], 'showposts' => $limit ) );
			foreach ( $home_posts as $home_post ) {
				$output .= '<li><a href="' . get_permalink( $home_post->ID ) . '">' . apply_filters( 'the_title',  $home_post->post_title ) . '</a></li>' . "\n";
			}
		} elseif ( is_single() || is_category() || is_date() || is_author() ) {

			switch ( $instance['post_display'] ) {
			case 'all_cat' :
				$child_of = 0;
				$widget_title = __( 'Category' );
				break;
			case 'root_cat' :
				if ( is_single() ) {
					$current = get_the_category();
					$child_of = array();
					foreach ( $current as $cat ) {
						$ancestors = get_ancestors( $cat->term_id, 'category' );
						if ( count( $ancestors ) ) {
							$child_of[] = array_pop( $ancestors );
						} else {
							$child_of[] = $cat->term_id;
						}
					}
					$child_of = array_unique( $child_of );
					$widget_title = count( $current ) == 1 ? $current[0]->name : __( 'Category' );
				} elseif ( is_category() ) {
					$current = get_category( get_query_var( 'cat' ) );
					$ancestors = get_ancestors( $current->term_id, 'category' );
					if ( count( $ancestors ) ) {
						$child_of = array_pop( $ancestors );
						$root = get_term( $child_of, 'category' );
						$widget_title = $root->name;
					} else {
						$child_of = $current->term_id;
						$widget_title = $current->name;
					}
				} else {
					$child_of = array();
					$widget_title = __( 'Category' );
				}

				break;
			case 'current_cat' :
				if ( is_single() ) {
					$current = get_the_category();
					$child_of = array();
					foreach ( $current as $cat ) {
						$child_of[] = $cat->term_id;
					}
					$widget_title = count( $current ) == 1 ? $current[0]->name : __( 'Category' );
				} elseif ( is_category() ) {
					$current = get_category( get_query_var( 'cat' ) );
					$child_of = $current->term_id;
					$widget_title = $current->name;
				} else {
					$child_of = array();
					$widget_title = __( 'Category' );
				}
				break;
			case 'y_archives' :
				$archive_type = 'yearly';
				$widget_title = __( 'Archives' );
				add_filter( 'get_archives_link', array( &$this, 'add_nen_for_get_archives' ) );
				break;
			case 'm_archives' :
				$archive_type = 'monthly';
				$widget_title = __( 'Archives' );
				break;

			default :
				return;
			}

			if ( $instance['post_title'] ) {
				$widget_title = $instance['post_title'];
			}
			if ( strpos( $instance['post_display'], 'archives' ) === false ) {
				if ( ! is_array( $child_of ) ) {
					$child_of = array( $child_of );
				}
				foreach ( $child_of as $cof ) {
					if ( $cof ) {
						$output .= wp_list_categories( array( 'echo' => 0, 'taxonomy' => 'category', 'title_li' => '', 'include' => $cof, 'hide_empty' => 0 ) );
					}
					$output .= wp_list_categories( array( 'echo' => 0, 'taxonomy' => 'category', 'title_li' => '', 'child_of' => $cof, 'orderby' => $instance['cat_orderby'], 'order' => $instance['cat_order'], 'show_option_none' => '' ) );
				}
			} else {
				$limit = absint( $instance['post_disp_nums'] ) ? absint( $instance['post_disp_nums'] ) : $this->defaults['post_disp_nums'];
				$output .= wp_get_archives( array( 'echo' => 0, 'type' => $archive_type, 'limit' => $limit ) );
			}
		} elseif ( is_page() && ! is_front_page() ) {
			$include_ids = '';
			switch ( $instance['page_display'] ) {
			case 'root' :
				$ancestors = get_post_ancestors( $post );
				if ( $ancestors ) {
					$child_of = array_pop( $ancestors );
					$root_post = get_post( $child_of );
					$widget_title = $root_post->post_title;	
				} else {
					$child_of = $post->ID;
					$widget_title = $post->post_title;
				}
				break;
			case 'root_current' :
				$ancestors = get_post_ancestors( $post );
				$instance['page_disp_level'] = count( $ancestors ) + $instance['page_disp_level'];
				if ( $ancestors ) {
					$child_of = array_pop( $ancestors );
					$root_post = get_post( $child_of );
					$widget_title = $root_post->post_title;	
					$exclude_ids = array();
					$first_children = get_children( array( 'post_parent' => $child_of, 'post_type' => 'page', 'post_status' => 'publish', 'exclude' => $ancestors ? $ancestors[count($ancestors)-1] : $post->ID ) );
					if ( $first_children ) {
						$tmp_arr = array();
						foreach ( $first_children as $first_child ) {
							$tmp_arr[] = $first_child->ID;
						}
						$ancestors = array_merge( $ancestors, $tmp_arr );
					}
					if ( $ancestors ) {
						foreach ( $ancestors as $ancestor ) {
							$children = get_children( array( 'post_parent' => $ancestor, 'post_type' => 'page', 'post_status' => 'publish', 'exclude' => $post->ID . ',' .implode( ',', $ancestors ) ) );
							if ( $children ) {
								foreach ( $children as $child ) {
									$exclude_ids[] = $child->ID;
								}
							}
						}
						$instance['page_exclude_tree'] = $instance['page_exclude_tree'] ? $instance['page_exclude_tree'] . ',' . implode( ',', $exclude_ids ) : implode( ',', $exclude_ids );
					}
				} else {
					$child_of = $post->ID;
					$widget_title = $post->post_title;
				}

				break;
			case 'current' :
				$child_of = $post->ID;
				$widget_title = $post->post_title;
				break;
			default :
				return;
			}
			
			if ( $instance['page_title'] ) {
				$widget_title = $instance['page_title'];
			}
			
			$depth = absint( $instance['page_disp_level'] ) ? absint( $instance['page_disp_level'] ) : $this->defaults['page_disp_level'];

			if ( $instance['page_display_child_of'] && ! in_array( $child_of, explode( ',', $instance['page_exclude_tree'] ) ) ) {
				$output .= wp_list_pages( 'echo=0&title_li=&include=' . $child_of );
			}
			$output .= wp_list_pages( 'echo=0&title_li=&child_of=' . $child_of . '&depth=' . $depth . '&exclude_tree=' . $instance['page_exclude_tree'] );
		} elseif ( get_query_var( 'taxonomy' ) && is_taxonomy_hierarchical( get_query_var( 'taxonomy' ) ) ) {
/*
			$taxonomy = get_query_var( 'taxonomy' ) ? get_query_var( 'taxonomy' ) : 'category';
			if ( is_category() ) {
				$current = get_category( get_query_var( 'cat' ) );
			} else {
				$current = get_term_by( 'slug', get_query_var( 'term' ), $taxonomy );
			}
			switch ( $instance['cat_display'] ) {
			case 'all' :
				$child_of = 0;
				$taxonomy_object = get_taxonomy( $taxonomy );
				$widget_title = $taxonomy == 'category' ? __( 'Category' ) : $taxonomy_object->label;
				break;
			case 'root' :
				$ancestors = get_ancestors( $current->term_id, $taxonomy );
				if ( count( $ancestors ) ) {
					$child_of = array_pop( $ancestors );
					$root = get_term( $child_of, $taxonomy );
					$widget_title = $root->name;
				} else {
					$child_of = $current->term_id;
					$widget_title = $current->name;
				}
				break;
			case 'current' :
				$child_of = $current->term_id;
				$widget_title = $current->name;
				break;
			default :
				return;
			}
			if ( $instance['cat_title'] ) {
				$widget_title = $instance['cat_title'];
			}
			if ( $child_of ) {
				$output .= wp_list_categories( array( 'echo' => 0, 'taxonomy' => $taxonomy, 'title_li' => '', 'include' => $child_of, 'hide_empty' => 0 ) );
			}
			$output .= wp_list_categories( array( 'echo' => 0, 'taxonomy' => $taxonomy, 'title_li' => '', 'child_of' => $child_of, 'orderby' => $instance['cat_orderby'], 'order' => $instance['cat_order'], 'show_option_none' => '' ) );
*/
		}
		if ( $output ) {
			echo $args['before_widget'] . "\n";
			echo $args['before_title'] . apply_filters( 'the_title', $widget_title ) . $args['after_title'] . "\n";
			echo '<ul class="sub_navi">' . "\n";
			echo $output;
			echo "</ul>\n";
			echo $args['after_widget'] . "\n";

		}
	}

	function update( $new_instance, $old_instance ) {
		// validate
		if ( ! isset( $new_instance['page_display_child_of'] ) ) {
			$new_instance['page_display_child_of'] = 0;
		}
		if ( ! isset( $new_instance['page_exclude_other_child'] ) ) {
			$new_instance['page_exclude_other_child'] = 0;
		}

		$new_instance['page_exclude_tree'] = mb_convert_kana( $new_instance['page_exclude_tree'], 'a' );
		$new_instance['page_exclude_tree'] = preg_replace( '/[^\d,]/', '', $new_instance['page_exclude_tree'] );
		$new_instance['page_exclude_tree'] = preg_replace( '/[,]+/', ',', $new_instance['page_exclude_tree'] );
		$new_instance['page_exclude_tree'] = trim( $new_instance['page_exclude_tree'], ',' );
		return $new_instance;
	}
 
	function form( $instance ) {
//		var_dump( $instance );
		$custom_post_types = get_post_types( array( 'public' => true, '_builtin' => false, 'publicly_queryable' => true ), false );
		$instance = wp_parse_args( (array)$instance, $this->defaults );
?>

	<h5>ホーム</h5>
	<p>表示内容：<br />
		<select name="<?php echo $this->get_field_name('home_display'); ?>">
			<option value="none">表示しない</option>
			<option value="latest"<?php echo $instance['home_display'] == 'latest' ? ' selected="selected"' : ''; ?>>最新情報</option>
			<option value="modify"<?php echo $instance['home_display'] == 'modify' ? ' selected="selected"' : ''; ?>>更新情報</option>
		</select>
	</p>
	<p>タイトル：<br />
		<input type="text" size="30" name="<?php echo $this->get_field_name('home_title'); ?>" value="<?php echo esc_html( $instance['home_title'] ); ?>" />
	</p>
	<p>表示数：<br />
		<input type="text" size="2" name="<?php echo $this->get_field_name('home_disp_nums'); ?>" value="<?php echo esc_html( $instance['home_disp_nums'] ); ?>" />
	</p>
	<p>表示タイプ：<br />
		<label for="<?php echo $this->get_field_name('home_post_type'); ?>_post">
			<input type="checkbox" name="<?php echo $this->get_field_name('home_post_type'); ?>[]" id="<?php echo $this->get_field_name('home_post_type'); ?>_post" value="post"<?php echo in_array( 'post', $instance['home_post_type'] ) ? ' checked="checked"' : ''; ?> />
			投稿
		</label>
		<label for="<?php echo $this->get_field_name('home_post_type'); ?>_page">
			<input type="checkbox" name="<?php echo $this->get_field_name('home_post_type'); ?>[]" id="<?php echo $this->get_field_name('home_post_type'); ?>_page" value="page"<?php echo in_array( 'page', $instance['home_post_type'] ) ? ' checked="checked"' : ''; ?> />
			固定ページ
		</label>
<?php if ( $custom_post_types ) : foreach ( $custom_post_types as $type_slug => $custom_post_type ) : ?>
		<label for="<?php echo $this->get_field_name('home_post_type'); ?>_<?php echo esc_attr( $type_slug ); ?>">
			<input type="checkbox" name="<?php echo $this->get_field_name('home_post_type'); ?>[]" id="<?php echo $this->get_field_name('home_post_type'); ?>_<?php echo esc_attr( $type_slug ); ?>" value="<?php echo esc_attr( $type_slug ); ?>"<?php echo in_array( $type_slug, $instance['home_post_type'] ) ? ' checked="checked"' : ''; ?> />
			<?php echo esc_html( $custom_post_type->label ); ?>
		</label>
<?php endforeach; endif; ?>
	</p>
	<h5>投稿・カテゴリー・タグ・アーカイブ・作成者</h5>
	<p>表示内容：<br />
		<select name="<?php echo $this->get_field_name( 'post_display' ); ?>">
			<option value="none">表示しない</option>
			<option value="all_cat"<?php echo $instance['post_display'] == 'all_cat' ? ' selected="selected"' : ''; ?>>すべてのカテゴリー</option>
			<option value="root_cat"<?php echo $instance['post_display'] == 'root_cat' ? ' selected="selected"' : ''; ?>>最上位カテゴリーの子カテゴリー</option>
			<option value="current_cat"<?php echo $instance['post_display'] == 'current_cat' ? ' selected="selected"' : ''; ?>>表示中の子カテゴリー</option>
			<option value="y_archives"<?php echo $instance['post_display'] == 'y_archives' ? ' selected="selected"' : ''; ?>>年別アーカイブ</option>
			<option value="m_archives"<?php echo $instance['post_display'] == 'm_archives' ? ' selected="selected"' : ''; ?>>月別アーカイブ</option>
		</select>
	</p>
	<p>タイトル：<br />
		<input type="text" size="30" name="<?php echo $this->get_field_name( 'post_title' ); ?>" value="<?php echo esc_html( $instance['post_title'] ); ?>" />
	</p>
	<p>カテゴリー表示順：<br />
		<select name="<?php echo $this->get_field_name( 'cat_orderby' ); ?>">
			<option value="name"<?php echo $instance['cat_orderby'] == 'name' ? ' selected="selected"' : ''; ?>>名前順</option>
			<option value="slug"<?php echo $instance['cat_orderby'] == 'slug' ? ' selected="selected"' : ''; ?>>カテゴリースラッグ</option>
			<option value="count"<?php echo $instance['cat_orderby'] == 'count' ? ' selected="selected"' : ''; ?>>投稿数</option>
		</select>
		<select name="<?php echo $this->get_field_name( 'cat_order' ); ?>">
			<option value="asc"<?php echo $instance['cat_order'] == 'asc' ? ' selected="selected"' : ''; ?>>昇順</option>
			<option value="desc"<?php echo $instance['cat_order'] == 'desc' ? ' selected="selected"' : ''; ?>>降順</option>
		</select>
	</p>

	<p>アーカイブ表示数：<br />
		<input type="text" size="2" name="<?php echo $this->get_field_name('post_disp_nums'); ?>" value="<?php echo esc_html( $instance['post_disp_nums'] ); ?>" />
	</p>

	<h5>固定ページ</h5>
	<p>表示内容：<br />
		<select name="<?php echo $this->get_field_name( 'page_display' ); ?>">
			<option value="none">表示しない</option>
			<option value="root"<?php echo $instance['page_display'] == 'root' ? ' selected="selected"' : ''; ?>>最上位を基点とした下層ページを表示</option>
			<option value="root_current"<?php echo $instance['page_display'] == 'root_current' ? ' selected="selected"' : ''; ?>>最上位を基点、第一、上位、子ページを表示</option>
			<option value="current"<?php echo $instance['page_display'] == 'current' ? ' selected="selected"' : ''; ?>>現在地を基点とした下層ページを表示</option>
		</select><br />
		<input type="checkbox" id="<?php echo $this->get_field_name( 'page_display_child_of' ); ?>" name="<?php echo $this->get_field_name( 'page_display_child_of' ); ?>" value="1"<?php echo $instance['page_display_child_of'] ? ' checked="checked"' : ''; ?> />
		<label for="<?php echo $this->get_field_name( 'page_display_child_of' ); ?>">基点ページを表示する</label>
	</p>
	<p>タイトル：<br />
		<input type="text" size="30" name="<?php echo $this->get_field_name( 'page_title' ); ?>" value="<?php echo esc_html( $instance['page_title'] ); ?>" />
	</p>

	<p>表示階層数：<br />
		<input type="text" size="3" name="<?php echo $this->get_field_name('page_disp_level'); ?>" value="<?php echo esc_html( $instance['page_disp_level'] ); ?>" />
	</p>
	<p>除外ページID：<br />
		<input type="text" size="30" name="<?php echo $this->get_field_name('page_exclude_tree'); ?>" value="<?php echo esc_html( $instance['page_exclude_tree'] ); ?>" />
	</p>

<?php
	}
	
	
	function add_nen_for_get_archives( $link_html ) {
		$regex = array ( 
			"/ title='([\d]{4})'/"	=> " title='$1年'",
			"/ ([\d]{4}) /"			=> " $1年 ",
			"/>([\d]{4})<\/a>/"		=> ">$1年</a>"
		);
		$link_html = preg_replace( array_keys( $regex ), $regex, $link_html );
		return $link_html;
	}
} // widget end

function register_subnavi_widget() {
    register_widget( 'sub_navi_widget' );
}
add_action('widgets_init', 'register_subnavi_widget');
