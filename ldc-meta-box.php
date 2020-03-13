<?php
/**
 * Author: Luis del Cid
 * Author URI: https://luisdelcid.com
 * Description: Just another LDC plugin.
 * Domain Path:
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network:
 * Plugin Name: LDC Meta Box
 * Plugin URI: https://github.com/luisdelcid/ldc-meta-box
 * Text Domain: ldc-meta-box
 * Version: 0.3.10
 */

	defined('ABSPATH') or die('No script kiddies please!');
		add_action('ldc_plugin_loaded', function(){
		if(!class_exists('LDC_Meta_Box', false)){
			require_once(plugin_dir_path(__FILE__) . 'classes/class-ldc-meta-box.php');
			LDC_Meta_Box::init(__FILE__, '0.3.10');
		}
	});
