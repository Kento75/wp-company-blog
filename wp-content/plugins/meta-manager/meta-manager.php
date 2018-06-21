<?php
/**
 * @package Meta Manager
 * @version 1.0.8
 */
/*
Plugin Name: Meta Manager
Plugin URI: http://www.warna.info/
Description: Outputs meta description and meta keywords in the element head.
Author: Hitoshi Omagari
Version: 1.0.8
Author URI: http://www.warna.info/
*/

class Meta_Manager {
	var $default = array(
		'includes_taxonomies'		=> array(),
		'excerpt_as_description'	=> true,
		'include_term'				=> true,
	);
	
	var $setting;
	var $term_keywords;
	var $term_description;

public function __construct() {
	if ( is_admin() ) {
		add_action( 'add_meta_boxes'					, array( &$this, 'add_post_meta_box' ), 10, 2 );
		add_action( 'wp_insert_post'					, array( &$this, 'update_post_meta' ) );
		add_action( 'admin_menu'						, array( &$this, 'add_setting_menu' ) );
		add_action( 'admin_print_styles-settings_page_meta-manager', array( &$this, 'print_icon_style' ) );
		add_action( 'plugins_loaded'					, array( &$this, 'update_settings' ) );
		add_filter( 'plugin_action_links'				, array( &$this, 'plugin_action_links' ), 10, 2 );
		add_action( 'admin_print_styles-post.php'		, array( &$this, 'print_metabox_styles' ) );
		add_action( 'admin_print_styles-post-new.php'	, array( &$this, 'print_metabox_styles' ) );
		register_deactivation_hook( __FILE__ , array( &$this, 'deactivation' ) );
	}

	add_action( 'wp_loaded',	array( &$this, 'taxonomy_update_hooks' ), 9999 );
	add_action( 'wp_head',		array( &$this, 'output_meta' ), 0 );

	$this->term_keywords = get_option( 'term_keywords' );
	$this->term_description = get_option( 'term_description' );
	if ( ! $this->setting = get_option( 'meta_manager_settings' ) ) {
		$this->setting = $this->default;
	}
	
}


public function taxonomy_update_hooks() {
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ) );
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			add_action( $taxonomy . '_add_form_fields', array( $this, 'add_keywords_form' ) );
			add_action( $taxonomy . '_edit_form_fields', array( &$this, 'edit_keywords_form' ), 0, 2 );
			add_action( 'created_' . $taxonomy, array( &$this, 'update_term_meta' ) );
			add_action( 'edited_' . $taxonomy, array( &$this, 'update_term_meta' ) );
			add_action( 'delete_' . $taxonomy, array( &$this, 'delete_term_meta' ) );
		}
	}
}


public function plugin_action_links( $links, $file ) {
	$status = get_query_var( 'status' ) ? get_query_var( 'status' ) : 'all';
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$s = get_query_var( 's' );
	$this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ) {
		$link = trailingslashit( get_bloginfo( 'wpurl' ) ) . 'wp-admin/options-general.php?page=meta-manager.php';
		$tax_regist_link = '<a href="' . $link . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $tax_regist_link ); // before other links
		$link = wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . $this_plugin . '&amp;plugin_status=' . $status . '&amp;paged=' . $paged . '&amp;deloption=1&amp;s=' . $s, 'deactivate-plugin_' . $this_plugin );
		$del_setting_deactivation_link = '<a href="' . $link . '">設定を削除して停止</a>';
		array_push( $links, $del_setting_deactivation_link );
	}
	return $links;
}



public function deactivation() {
	if ( isset( $_GET['deloption'] ) && $_GET['deloption'] ) {
		delete_option( 'meta_keywords' );
		delete_option( 'meta_description' );
		delete_option( 'meta_manager_settings' );
		delete_post_meta_by_key( '_keywords' );
		delete_post_meta_by_key( '_description' );
	}
}


public function add_keywords_form() {
?>
<div class="form-field">
	<label for="meta_keywords">メタキーワード</label>
	<textarea name="meta_keywords" id="meta_keywords" rows="3" cols="40"></textarea>
</div>
<div class="form-field">
	<label for="meta_description">メタディスクリプション</label>
	<textarea name="meta_description" id="meta_description" rows="5" cols="40"></textarea>
</div>
<?php
}


public function edit_keywords_form( $tag ) {
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="meta_keywords">メタキーワード</label></th>
		<td><input type="text" name="meta_keywords" id="meta_keywords" size="40" value="<?php echo isset( $this->term_keywords[$tag->term_id] ) ? esc_html( $this->term_keywords[$tag->term_id] ) : ''; ?>" />
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="meta_description">メタディスクリプション</label></th>
		<td><textarea name="meta_description" id="meta_description" cols="40" rows="5"><?php echo isset( $this->term_description[$tag->term_id] ) ? esc_html( $this->term_description[$tag->term_id] ) : ''; ?></textarea>
	</tr>
<?php
}


public function update_term_meta( $term_id ) {
	if ( isset( $_POST['meta_keywords'] ) ) {
		$post_keywords = stripslashes_deep( $_POST['meta_keywords'] );
		$post_keywords = $this->get_unique_keywords( $post_keywords );
		if ( ! isset( $this->term_keywords[$term_id] ) || $this->term_keywords[$term_id] != $post_keywords ) {
			$this->term_keywords[$term_id] = $post_keywords;
			update_option( 'term_keywords', $this->term_keywords );
		}
	}

	if ( isset( $_POST['meta_description'] ) ) {
		$post_description = stripslashes_deep( $_POST['meta_description'] );
		if ( ! isset( $this->term_description[$term_id] ) || $this->term_description[$term_id] != $post_description ) {
			$this->term_description[$term_id] = $post_description;
			update_option( 'term_description', $this->term_description );
		}
	}
}


public function delete_term_meta( $term_id ) {
	if ( isset( $this->term_keywords[$term_id] ) ) {
		unset( $this->term_keywords[$term_id] );
		update_option( 'term_keywords', $this->term_keywords );
	}
	if ( isset( $this->term_description[$term_id] ) ) {
		unset( $this->term_description[$term_id] );
		update_option( 'term_description', $this->term_description );
	}
}


public function add_post_meta_box( $post_type, $post ) {
	$post_type_obj = get_post_type_object( $post_type );
	$output_flag = apply_filters( 'meta_manager_meta_box_display', true, $post_type, $post );
	if ( $post_type_obj && $post_type_obj->public && $output_flag ) {
		add_meta_box( 'post_meta_box', 'メタ情報', array( &$this, 'post_meta_box' ), $post_type, 'normal', 'high');
	}
}


public function post_meta_box() {
	global $post;
	$post_keywords = get_post_meta( $post->ID, '_keywords', true ) ? get_post_meta( $post->ID, '_keywords', true ) : '';
	$post_description = get_post_meta( $post->ID, '_description', true ) ? get_post_meta( $post->ID, '_description', true ) : '';
?>
<dl>
	<dt>メタキーワード</dt>
	<dd><input type="text" name="_keywords" id="post_keywords" size="100" value="<?php echo esc_html( $post_keywords ); ?>" /></dd>
	<dt>メタディスクリプション</dt>
	<dd><textarea name="_description" id="post_description" cols="100" rows="3"><?php echo esc_html( $post_description ); ?></textarea></dd>
</dl>
<?php
}


public function print_metabox_styles() {
?>
<style type="text/css" charset="utf-8">
#post_keywords,
#post_description {
	width: 98%;
}
</style>
<?php
}


public function update_post_meta( $post_ID ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['_keywords'] ) ) {
		$post_keywords = stripslashes_deep( $_POST['_keywords'] );
		$post_keywords = $this->get_unique_keywords( $post_keywords );
		update_post_meta( $post_ID, '_keywords', $post_keywords );
	}
	if ( isset( $_POST['_description'] ) ) {
		$post_keywords = stripslashes_deep( $_POST['_description'] );
		update_post_meta( $post_ID, '_description', $post_keywords );
	}
}


public function output_meta() {
	$meta = $this->get_meta();
	$output = '';
	if ( $meta['keywords'] ) {
		$output .= '<meta name="keywords" content="' . esc_attr( $meta['keywords'] ) . '" />' . "\n";
	}
	if ( $meta['description'] ) {
		$output .= '<meta name="description" content="' . esc_attr( $meta['description'] ) . '" />' . "\n";
	}
	echo $output;
}


private function get_meta() {
	$meta = array();
	$option = array();
	$meta['keywords'] = get_option( 'meta_keywords' ) ? get_option( 'meta_keywords' ) : '';
	$meta['description'] = get_option( 'meta_description' ) ? get_option( 'meta_description' ) : '';
	if ( is_singular() ) {
		$option = $this->get_post_meta();
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$option = $this->get_term_meta();
	}

	if ( ! empty( $option ) && $option['keywords'] ) {
		$meta['keywords'] = $this->get_unique_keywords( $option['keywords'], $meta['keywords'] );
	} else {
		$meta['keywords'] = $this->get_unique_keywords( $meta['keywords'] );
	}
	
	if ( ! empty( $option ) && $option['description'] ) {
		$meta['description'] = $option['description'];
	}
	$meta['description'] = mb_substr( $meta['description'], 0, 120, 'UTF-8' );
	return $meta;
}

private function get_post_meta() {
	global $post;
	$post_meta = array();
	$post_meta['keywords'] = get_post_meta( $post->ID, '_keywords', true ) ? get_post_meta( $post->ID, '_keywords', true ) : '';
	if ( ! empty( $this->setting['includes_taxonomies'] ) ) {
		foreach ( $this->setting['includes_taxonomies'] as $taxonomy ) {
			$taxonomy = get_taxonomy( $taxonomy );
			if ( in_array( $post->post_type, $taxonomy->object_type ) ) {
				$terms = get_the_terms( $post->ID, $taxonomy->name );
				if ( $terms ) {
					$add_keywords = array();
					foreach ( $terms as $term ) {
						$add_keywords[] = $term->name;
					}
					$add_keywords = implode( ',', $add_keywords );
					if ( $post_meta['keywords'] ) {
						$post_meta['keywords'] .= ',' . $add_keywords;
					} else {
						$post_meta['keywords'] = $add_keywords;
					}
				}
			}
		}
	}
	$post_meta['description'] = get_post_meta( $post->ID, '_description', true ) ? get_post_meta( $post->ID, '_description', true ) : '';
	if ( $this->setting['excerpt_as_description'] && ! $post_meta['description'] ) {
		if ( trim( $post->post_excerpt ) ) {
			$post_meta['description'] = $post->post_excerpt;
		} else {
			$excerpt = apply_filters( 'the_content', $post->post_content );
			$excerpt = strip_shortcodes( $excerpt );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt = strip_tags( $excerpt );
			$post_meta['description'] = trim( preg_replace( '/[\n\r\t ]+/', ' ', $excerpt), ' ' );
		}
	}
	return $post_meta;
}


private function get_term_meta() {
	$term_meta = array();
	if ( is_tax() ) {
		$taxonomy = get_query_var( 'taxonomy' );
		$slug = get_query_var( 'term' );
		$term = get_term_by( 'slug', $slug, $taxonomy );
		$term_id = $term->term_id;
	} elseif ( is_category() ) {
		$term_id = get_query_var( 'cat' );
		$term = get_category( $term_id );
	} elseif ( is_tag() ) {
		$slug = get_query_var( 'tag' );
		$term = get_term_by( 'slug', $slug, 'post_tag' );
		$term_id = $term->term_id;
	}

	$term_meta['keywords'] = isset( $this->term_keywords[$term_id] ) ? $this->term_keywords[$term_id] : '';
	if ( $this->setting['include_term'] ) {
		$term_meta['keywords'] = $term->name . ',' . $term_meta['keywords'];
	}
	$term_meta['description'] = isset( $this->term_description[$term_id] ) ? $this->term_description[$term_id] : '';
	return $term_meta;
}


private function get_unique_keywords() {
	$args = func_get_args();
	$keywords = array();
	if ( ! empty( $args ) ) {
		foreach ( $args as $arg ) {
			if ( is_string( $arg ) ) {
				$keywords[] = trim( $arg, ', ' );
			}
		}
		$keywords = implode( ',', $keywords );
		$keywords = preg_replace( '/[, ]*,[, ]*/', ',', $keywords );
		$keywords = explode( ',', $keywords );
		foreach ( $keywords as $key => $keyword ) {
			if ( ! $keyword ) {
				unset( $keywords[$key] );
			}
		}
		$keywords = array_map( 'trim', $keywords );
		$keywords = array_unique( $keywords );
	}
	$keywords = implode( ',', $keywords );
	return $keywords;
}


public function add_setting_menu() {
	add_options_page( 'Meta Manager', 'Meta Manager', 'manage_options', basename( __FILE__ ), array( &$this, 'setting_page' ) );
}


public function setting_page() {
	$meta_keywords = get_option( 'meta_keywords' ) ? get_option( 'meta_keywords' ) : '';
	$meta_description = get_option( 'meta_description' ) ? get_option( 'meta_description' ) : '';
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ), false );
?>
<div class="wrap">
	<?php screen_icon( 'meta_manager-icon32' ); ?><h2>Meta Manager</h2>
	<form action="" method="post">
<?php wp_nonce_field( 'meta_manager' ); ?>
		<h3>サイトワイド設定</h3>
		<table class="form-table">
			<tr>
				<th>共通キーワード</th>
				<td>
					<label for="meta_keywords">
						<input type="text" name="meta_keywords" id="meta_keywords" size="100" value="<?php echo esc_html( $meta_keywords ); ?>" />
					</label>
				</td>
			</tr>
			<tr>
				<th>基本ディスクリプション</th>
				<td>
					<label for="meta_description">
						<textarea name="meta_description" id="meta_description" cols="100" rows="3"><?php echo esc_html( $meta_description ); ?></textarea>
					</label>
				</td>
			</tr>
		</table>
		<h3>記事設定</h3>
		<table class="form-table">
<?php if ( $taxonomies ) : $cnt = 1; ?>
			<tr>
				<th>記事のキーワードに含める分類</th>
				<td>
<?php foreach ( $taxonomies as $tax_slug => $taxonomy ) : ?>
					<label for="includes_taxonomies-<?php echo $cnt; ?>">
						<input type="checkbox" name="includes_taxonomies[]" id="includes_taxonomies-<?php echo $cnt; ?>" value="<?php echo esc_html( $tax_slug ); ?>"<?php echo in_array( $tax_slug, $this->setting['includes_taxonomies'] ) ? ' checked="checked"' : ''; ?> />
						<?php echo esc_html( $taxonomy->labels->singular_name ); ?>
					</label>
<?php $cnt++; endforeach; ?>
				</td>
			</tr>
<?php endif; ?>
			<tr>
				<th>記事のメタディスクリプション</th>
				<td>
					<label for="excerpt_as_description">
						<input type="checkbox" name="excerpt_as_description" id="excerpt_as_description" value="1"<?php echo $this->setting['excerpt_as_description'] ? ' checked="checked"' : ''; ?> />
						抜粋を記事のディスクリプションとして利用する
					</label>
				</td>
			</tr>
		</table>
		<h3>タクソノミー設定</h3>
		<table class="form-table">
			<tr>
				<th>タクソノミーのメタキーワード</th>
				<td>
					<label for="include_term">
						<input type="checkbox" name="include_term" id="include_term" value="1"<?php echo $this->setting['include_term'] ? ' checked="checked"' : ''; ?> />
						分類名をキーワードに含める
					</label>
				</td>
			</tr>

		</table>
		<p><input type="submit" name="meta_manager_update" value="<?php _e( 'Save Changes' ); ?>" class="button-primary" /></p>
	</form>
	<div id="developper_information">
		<div id="poweredby">
			<a href="http://www.prime-strategy.co.jp/" target="_blank"><img src="<?php echo preg_replace( '/^https?:/', '', plugin_dir_url( __FILE__ ) ) . 'images/ps_logo.png'; ?>" alt="Powered by Prime Strategy" /></a>
		</div>
	</div>
</div>
<?php
}


public function update_settings() {
	if ( isset( $_POST['meta_manager_update'] ) ) {
		$post_data = stripslashes_deep( $_POST );
		check_admin_referer( 'meta_manager' );
		$setting = array();
		foreach ( $this->default as $key => $def ) {
			if ( ! isset( $post_data[$key] ) ) {
				if ( $key == 'includes_taxonomies' ) {
					$setting['includes_taxonomies'] = array();
				} else {
					$setting[$key] = false;
				}
			} else {
				if ( $key == 'includes_taxonomies' ) {
					$setting['includes_taxonomies'] = $post_data['includes_taxonomies'];
				} else {
					$setting[$key] = true;
				}
			}
		}
		$meta_keywords = $this->get_unique_keywords( $post_data['meta_keywords'] );
		update_option( 'meta_keywords', $meta_keywords );
		update_option( 'meta_description', $post_data['meta_description'] );
		update_option( 'meta_manager_settings', $setting );
		$this->setting = $setting;
	}
}


public function print_icon_style() {
	$url = preg_replace( '/^https?:/', '', plugin_dir_url( __FILE__ ) ) . 'images/icon32.png';
?>
<style type="text/css" charset="utf-8">
#icon-meta_manager-icon32 {
	background: url( <?php echo esc_url( $url ); ?> ) no-repeat center;
}
#developper_information {
	margin: 20px 30px 10px;
}
#developper_information .content {
	padding: 10px 20px;
}
#poweredby {
	text-align: right;
}
</style>
<?php
}


} // class end
$meta_manager = new Meta_Manager;