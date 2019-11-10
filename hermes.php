<?php

/**
 * Hermes Plugins
 *
 * The Hermes plugin is designed to make the calculation of
 * materials needed by movers companies faster and easier.
 *
 * @since             1.0.0
 * @package           Hermes
 *
 * @wordpress-plugin
 * Plugin Name:       Hermes
 * Description:        International Movers Calculator
 * Version:           1.0.0
 * Author:            Aleksandar Netsov
 * Author URI:        
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:            hermes
 * Domain Path:            /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-hermes-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-hermes-deactivator.php';

/** This action is documented in includes/class-hermes-activator.php */
register_activation_hook( __FILE__, array( 'Hermes_Activator', 'activate' ) );

/** This action is documented in includes/class-hermes-deactivator.php */
register_activation_hook( __FILE__, array( 'Hermes_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-hermes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Hermes() {

	$plugin = new Hermes();
	$plugin->run();

}
run_Hermes();
