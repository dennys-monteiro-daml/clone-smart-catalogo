<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              brunopolo.com.br
 * @since             1.0.0
 * @package           Pdf_To_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       PDF to Woocommerce
 * Plugin URI:        brunopolo.com.br
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Bruno Polo
 * Author URI:        brunopolo.com.br
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pdf-to-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PDF_TO_WOOCOMMERCE_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pdf-to-woocommerce-activator.php
 */
function activate_pdf_to_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-to-woocommerce-activator.php';
	Pdf_To_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pdf-to-woocommerce-deactivator.php
 */
function deactivate_pdf_to_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-to-woocommerce-deactivator.php';
	Pdf_To_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pdf_to_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_pdf_to_woocommerce' );


define("DATA_LAYER_CONFIG", [
    "driver" => "mysql",
    "host" => DB_HOST,
    "port" => "3306",
    "dbname" => DB_NAME,
    "username" => DB_USER,
    "passwd" => DB_PASSWORD,
    "options" => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]
]);

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pdf-to-woocommerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pdf_to_woocommerce() {

	$plugin = new Pdf_To_Woocommerce();
	$plugin->run();

}
run_pdf_to_woocommerce();
