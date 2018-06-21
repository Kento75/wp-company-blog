<?php
/**
 * @package 001 Prime Strategy Translate Accelerator
 */
/*
Plugin Name: 001 Prime Strategy Translate Accelerator
Plugin URI: http://www.web-strategy.jp/wp_plugin/translate_accelerator/
Description: This plugin makes the translation cache files, and shortens the execution time to display your WordPress site.
Version: 1.0.9
Author: Prime Strategy Co.,Ltd
Author URI: http://www.prime-strategy.co.jp/
License: GPLv2 or later
Text Domain: prime-strategy-transelate-accelerator
Domain Path: /languages/

php version 5.3.6, 5.2.5, 5.1.6
WordPress Version 2.9.2 - 3.2.0
apc version 3.0.19, 3.1.6

caceh_type : apc,file
frontend,wp-login,admin : cache, none, cutoff
*/
class Prime_Strategy_Translate_Accelerator {

var $setting;
var $default;
var $file_cache_dir;
var $version = '1.0.9';

function Prime_Strategy_Translate_Accelerator() {
	$this->__construct();
}

function __construct() {
	
	add_action( 'plugins_loaded'	, array( &$this, 'load_plugin_textdomain' ) );
	if ( is_admin() ) {
		add_filter('plugin_action_links', array( &$this, 'plugin_setting_links' ), 10, 2 );
		add_action( 'admin_menu'		, array( &$this, 'add_setting_menu' ) );
		add_action( 'plugins_loaded'	, array( &$this, 'update_psta_settings' ) );
		add_action( 'admin_print_styles-settings_page_001-prime-strategy-translate-accelerator', array( &$this, 'print_icon_style' ) );
	}
	register_deactivation_hook( __FILE__ , array( &$this, 'psta_deactivation' ) );

	$this->default = array(
		'activate' => false,
		'cache_type' => 'file',
		'frontend' => 'false',
		'wp-login' => 'cache',
		'admin' => 'cache',
		'file_cache_dir' => '',
		'error_mes' => false,
	);
	
	$this->setting = get_option( 'psta_settings' );

	if ( ! $this->setting ) {
		$this->setting = array();
	}
	$this->setting = array_merge( $this->default, $this->setting );

	if ($this->setting['activate'] == true) {
		$this->check();
	}
}

function check() {
	$s =& $this->setting;

	if ( $s['cache_type'] == 'apc' ) {
		if ( ! function_exists( 'apc_store' ) ) {
			$this->error_mes( 'apc is not enable.' );
			return false;
		}
	}

	if ( $s['cache_type'] == 'file' ) {
		if ( $s['file_cache_dir'] == '' ) {
			$dir = plugin_dir_path( __FILE__ ) . 'cache';
			$dir = dirname(__FILE__);
			$dir = preg_replace( '/\\\\/', '/', $dir );
			$dir = $dir . '/cache';
		} else {
			$dir = $s['file_cache_dir'];
		}
		if ( !file_exists($dir) || !is_dir($dir) ) {
			$this->error_mes( 'file_cahce_dir is not exists.' );
			return false;
		}
		if (!is_writable($dir)) {
			$this->error_mes( 'file_cache_dir is not writable.' );
			return false;
		}
		$this->file_cache_dir = $dir;
	}

	add_filter( 'override_load_textdomain', array(&$this, 'load_textdomain'), 10, 3);
}


function update_psta_settings() {
	if ( isset( $_POST['psta_update'] ) ) {
		if ( isset( $_POST['cache_force_delete'] ) ) {
			switch ( $this->setting['cache_type'] ) {
			case 'apc' :
				if ( function_exists( 'apc_clear_cache' ) ) {
					apc_clear_cache( 'user' );
				}
				break;
			case 'file' :
				$this->file_cache_dir;
				if ( $dh = opendir( $this->file_cache_dir ) ) {
					while ( ( $file = readdir( $dh ) ) !== false ) {
						if ( is_file( $this->file_cache_dir . '/' . $file ) ) {
							@unlink( $this->file_cache_dir . '/' . $file );
						}
					}
				}
			}
		}
		$post_data = stripslashes_deep( $_POST );
		check_admin_referer( 'prime_strategy_translate_accelerator' );
		$setting = array();
		foreach ( $this->default as $key => $def ) {
			if ( $key == 'activate' && ! isset( $post_data['activate'] ) ) {
				$post_data['activate'] = false;
			}
			if ( $key != 'error_mes' ) {
				$setting[$key] = $post_data[$key];
			}
		}
		$this->setting = $setting;
		update_option( 'psta_settings', $setting );
	}
}


function load_plugin_textdomain() {
	load_plugin_textdomain( 'prime-strategy-transelate-accelerator', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}


function error_mes($mes) {
	$s =& $this->setting;
	if ( is_admin() && $s['error_mes'] == true) {
		echo $mes . '<br />';
	}
}

function load_textdomain( $dum, $domain, $mofile) {
	$s =& $this->setting;

	$segment = 'frontend';
	if ( is_admin() ) {
		$segment = 'admin';
	} elseif ( preg_match( '/wp-(login|signup|register)\.php/', $_SERVER['REQUEST_URI'] ) ) {
		$segment = 'wp-login';
	}

	if ( $s[$segment] == 'cutoff' ) {
		return true;
	} elseif ($s[$segment] == 'cache' ) {
		$this->cache_controle( $domain, $mofile );
		return true;
	}
	return false;
}

function cache_controle( $domain, $mofile ) {
	global $l10n;
	$s =& $this->setting;
	do_action( 'load_textdomain', $domain, $mofile );
	$mofile = apply_filters( 'load_textdomain_mofile', $mofile, $domain );
	if ( ! is_readable( $mofile ) ) return false;
	$mo = new MO();

	$cache = false;

	if ( $s['cache_type'] == 'apc' ) {
		$cache = apc_fetch( $mofile, $ret );
	} elseif ( $s['cache_type'] == 'file' ) {
		$file = preg_replace( '/^.*?wp-content/', '', $mofile );
		$file = preg_replace( '/\\\\|\//', '_', $file);
		$file = $this->file_cache_dir . '/' . $file;
		if ( file_exists( $file ) ) {
			$cache = file_get_contents( $file );
			$cache = unserialize( $cache );
		}
	}

	if ( is_object( $cache ) ) {
		$mo = $cache;
	} else {
		if ( !$mo->import_from_file( $mofile ) ) {
			return false;
		}
		$mo->_gettext_select_plural_form = null;

		if ($s['cache_type'] == 'apc') {
			apc_store($mofile, $mo);
		} elseif ($s['cache_type'] == 'file') {
			$cache = serialize($mo);
			file_put_contents( $file, $cache );
		}
	}

	if ( isset( $l10n[$domain] ) ) {
		$mo->merge_with( $l10n[$domain] );
	}
	$l10n[$domain] = &$mo;

}


function psta_deactivation() {
	delete_option( 'psta_settings' );
}


function add_setting_menu() {
	add_options_page( 'Translate Accelerator', 'Translate Accelerator', 'manage_options', basename( __FILE__ ), array( &$this, 'setting_page' ) );
}


function setting_page() {
	$s =& $this->setting;
?>
<div class="wrap">
	<?php screen_icon( 'psta-icon32' ); ?><h2>001 Prime Strategy Translate Accelerator</h2>
	<form action="" method="post">
<?php wp_nonce_field( 'prime_strategy_translate_accelerator' ); ?>

		<table class="form-table">
			<tr>
				<th><?php _e( 'Enable to cache the translation files.', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<label for="activate">
						<input type="checkbox" name="activate" id="activate" value="1"<?php echo $s['activate'] == 1 ? ' checked="checked"' : ''; ?> />
						<?php _e( 'Enable to cache the translation files.', 'prime-strategy-transelate-accelerator' ); ?>
					</label>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Cache type', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<ul>
						<li><label for="cache_type_file"><input type="radio" name="cache_type" id="cache_type_file" value="file"<?php echo $s['cache_type'] == 'file' || ( $s['cache_type'] == 'apc' && ! function_exists( 'apc_store' ) ) ? ' checked="checked"' : ''; ?> /> <?php _e( 'Files', 'prime-strategy-transelate-accelerator' ); ?></label><br />
							<?php _e( 'Cache directory :', 'prime-strategy-transelate-accelerator' ); ?> <input type="text" size="50" name="file_cache_dir" value="<?php echo esc_html( $s['file_cache_dir'] ); ?>" />
						</li>
<?php if ( function_exists( 'apc_store' ) ) : ?>						<li><label for="cache_type_apc"><input type="radio" name="cache_type" id="cache_type_apc" value="apc"<?php echo ( $s['cache_type'] == 'apc' ) && function_exists( 'apc_store' ) ? ' checked="checked"' : ''; ?> /> <?php _e( 'APC', 'prime-strategy-transelate-accelerator' ); ?></label></li><?php endif; ?>
					</ul>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Translated text displayed in your site', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<select name="frontend">
						<option value="cache"<?php echo $s['frontend'] == 'cache' ? ' selected="selected"' : ''; ?>><?php _e( 'Enable cache', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="cutoff"<?php echo $s['frontend'] == 'cutoff' ? ' selected="selected"' : ''; ?>><?php _e( 'Disable translation', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="defalut"<?php echo $s['frontend'] == 'defalut' ? ' selected="selected"' : ''; ?>><?php _e( 'Use language file/s for translation', 'prime-strategy-transelate-accelerator' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Login/signup page translation', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<select name="wp-login">
						<option value="cache"<?php echo $s['wp-login'] == 'cache' ? ' selected="selected"' : ''; ?>><?php _e( 'Enable cache', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="cutoff"<?php echo $s['wp-login'] == 'cutoff' ? ' selected="selected"' : ''; ?>><?php _e( 'Disable translation', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="defalut"<?php echo $s['wp-login'] == 'defalut' ? ' selected="selected"' : ''; ?>><?php _e( 'Use language file/s for translation', 'prime-strategy-transelate-accelerator' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Admin pages translation', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<select name="admin">
						<option value="cache"<?php echo $s['admin'] == 'cache' ? ' selected="selected"' : ''; ?>><?php _e( 'Enable cache', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="cutoff"<?php echo $s['admin'] == 'cutoff' ? ' selected="selected"' : ''; ?>><?php _e( 'Disable translation', 'prime-strategy-transelate-accelerator' ); ?></option>
						<option value="defalut"<?php echo $s['admin'] == 'defalut' ? ' selected="selected"' : ''; ?>><?php _e( 'Use language file/s for translation', 'prime-strategy-transelate-accelerator' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><?php _e( 'Delete cache', 'prime-strategy-transelate-accelerator' ); ?></th>
				<td>
					<label for="cache_force_delete">
						<input type="checkbox" name="cache_force_delete" id="cache_force_delete" value="1" />
						<?php _e( 'Force deletion of all cache', 'prime-strategy-transelate-accelerator' ); ?>
					</label>
				</td>
			</tr>
		</table>
		<p><input type="submit" name="psta_update" value="<?php _e( 'Save Changes' ); ?>" class="button-primary" /></p>
	</form>
	<div id="developper_information">
		<div id="poweredby">
			<a href="http://www.prime-strategy.co.jp/" target="_blank"><img src="<?php echo preg_replace( '/^https?:/', '', plugin_dir_url( __FILE__ ) ) . 'images/ps_logo.png'; ?>" alt="Powered by Prime Strategy" /></a>
		</div>
	</div>
</div>
<?php
	
}


function print_icon_style() {
	$url = preg_replace( '/^https?:/', '', plugin_dir_url( __FILE__ ) ) . 'images/icon32.png';
?>
<style type="text/css" charset="utf-8">
#icon-psta-icon32 {
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


function plugin_setting_links( $links, $file ) {
	$this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ) {
		$link = trailingslashit( get_bloginfo( 'wpurl' ) ) . 'wp-admin/options-general.php?page=' . basename( __FILE__ ); 
		$settings_link = '<a href="' . $link . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}

} // class end

$prime_strategy_translate_accelerator = new Prime_Strategy_Translate_Accelerator();
