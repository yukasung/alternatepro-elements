<?php
/**
 * Plugin Name: AlternatePro Elements
 * Description: Elementor Free extensions and builder foundations for the AlternatePro theme.
 * Version: 1.0.0
 * Author: AlternatePro
 * Text Domain: alternatepro-elements
 * Domain Path: /languages
 * Requires at least: 6.5
 * Requires PHP: 8.1
 *
 * @package AlternatePro\Elements
 */

defined( 'ABSPATH' ) || exit;

define( 'APRO_ELEMENTS_VERSION', '1.0.0' );
define( 'APRO_ELEMENTS_SCHEMA_VERSION', '3' );
define( 'APRO_ELEMENTS_FILE', __FILE__ );
define( 'APRO_ELEMENTS_PATH', plugin_dir_path( __FILE__ ) );
define( 'APRO_ELEMENTS_URL', plugin_dir_url( __FILE__ ) );
define( 'APRO_ELEMENTS_BASENAME', plugin_basename( __FILE__ ) );

require_once APRO_ELEMENTS_PATH . 'includes/Autoloader.php';

\AlternatePro\Elements\Autoloader::register();

/**
 * Returns the plugin singleton.
 *
 * @return \AlternatePro\Elements\Plugin
 */
function apro_elements() {
	return \AlternatePro\Elements\Plugin::instance();
}

add_action( 'plugins_loaded', 'apro_elements', 5 );

/**
 * Flush rewrites after registering plugin post types.
 *
 * @return void
 */
function apro_elements_activate() {
	\AlternatePro\Elements\Autoloader::register();

	if ( class_exists( '\AlternatePro\Elements\Activation' ) ) {
		\AlternatePro\Elements\Activation::activate();

		return;
	}

	flush_rewrite_rules();
}

/**
 * Flush rewrites on deactivation.
 *
 * @return void
 */
function apro_elements_deactivate() {
	\AlternatePro\Elements\Autoloader::register();

	if ( class_exists( '\AlternatePro\Elements\Activation' ) ) {
		\AlternatePro\Elements\Activation::deactivate();

		return;
	}

	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'apro_elements_activate' );
register_deactivation_hook( __FILE__, 'apro_elements_deactivate' );
