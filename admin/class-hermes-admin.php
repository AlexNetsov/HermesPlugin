<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Hermes
 * @subpackage Hermes/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hermes
 * @subpackage Hermes/admin
 * @author:    Aleksandar Netsov
 */
class Hermes_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hermes_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hermes_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/hermes-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hermes_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hermes_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/hermes-admin.js', array( 'jquery' ), $this->version, FALSE );

	}
	// Add Hermes options menu
	public function add_hermes_menu() {
	    //add hermes page to the menu
	    add_menu_page (
	        __('Hermes Options', 'hermes'),
	        __('Hermes', 'hermes'),
	        'manage_options',
			'hermes/admin/partials/hermes-admin-display.php',
			'',
			'',
			25	
	    );
	    add_submenu_page(
          'hermes/admin/partials/hermes-admin-display.php',          // admin page slug
          __( 'Hermes Options', 'hermes' ), // page title
          __( 'Options', 'hermes' ), // menu title
          'manage_options',               // capability required to see the page
          'hermes_options',                // admin page slug, e.g. options-general.php?page=wporg_options
          array($this, 'hermes_options_page_callback')            // callback function to display the options page
     );
	}
	
	public function add_hermes_options() {
		add_settings_field(
			'hermes_labour_setting',
			'Labour rate',
			array($this, 'hermes_labour_setting_callback'),
			'hermes_options',
			'hermes_main_settings'
		);
		register_setting(
          'hermes_options',
          'hermes_labour_setting'
		);
		
		add_settings_field(
			'hermes_to_cubics',
			'Cubics to which the pack load is constant',
			array($this, 'hermes_to_cubics_setting_callback'),
			'hermes_options',
			'hermes_main_settings'
		);
		register_setting(
          'hermes_options',
          'hermes_to_cubics'
		);
		
		add_settings_field(
			'hermes_labour_constant',
			'Labour Constant for the first X cubic meters',
			array($this, 'hermes_constant_labour_setting_callback'),
			'hermes_options',
			'hermes_main_settings'
		);
		register_setting(
          'hermes_options',
          'hermes_labour_constant'
		);
		
		add_settings_field(
			'hermes_admin_mail',
			'Admin mail which will recieve the entries',
			array($this, 'hermes_admin_mail_setting_callback'),
			'hermes_options',
			'hermes_main_settings'
		);
		register_setting(
          'hermes_options',
          'hermes_admin_mail'
		);
	}
	
	public function hermes_options_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'partials/hermes-options-display.php';
	}
	
	public function hermes_labour_setting_callback() {
		$setting = esc_attr( get_option( 'hermes_labour_setting' ) );
		echo "<input type='text' name='hermes_labour_setting' value='$setting' />";
	}
	public function hermes_to_cubics_setting_callback() {
		$setting = intval( get_option( 'hermes_to_cubics' ) );
		echo "<input type='number' name='hermes_to_cubics' value='$setting' />";
	}
	public function hermes_constant_labour_setting_callback() {
		$setting = esc_attr( get_option( 'hermes_labour_constant' ) );
		echo "<input type='number' name='hermes_labour_constant' value='$setting' />";
	}
	public function hermes_admin_mail_setting_callback() {
		$setting = esc_attr( get_option( 'hermes_admin_mail' ) );
		echo "<input type='email' name='hermes_admin_mail' value='$setting' />";
	}
	
	
/*
	function user_can_save( $post_id, $plugin_file, $nonce ) {
	
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $plugin_file ) );
		
		// Return true if the user is able to save; otherwise, false.
		return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
	
	}
	
	function has_files_to_upload( $id ) {
		return ( ! empty( $_FILES ) ) && isset( $_FILES[ $id ] );
	}
*/

	// CSV Import Functions
	function hermes_import_tables() {
		if ( isset($_REQUEST["insert_hermes_rooms"])){
			$this->import_table('hms_rooms', 'csv-rooms-upload');
		}
		else if (isset($_REQUEST["insert_hermes_house_types"])){
			$this->import_table('hms_house_types', 'csv-house-types-upload');
		}
		else if (isset($_REQUEST["insert_hermes_labour"])){
			$this->import_table('hms_labour', 'csv-labour-upload');
		}
		else if (isset($_REQUEST["insert_hermes_furniture"])){
			$this->import_table('hms_furniture', 'csv-furniture-upload');
		}
		else if (isset($_REQUEST["insert_hermes_packing_materials"])){
			$this->import_table('hms_packing_materials', 'csv-packing-materials-upload');
		}
		else {
			return;
		}
	}
	function import_table($table_suffix, $button_name) {
		global $wpdb;
		$table_name = $wpdb->prefix . $table_suffix;
		$wpdb->query("TRUNCATE TABLE $table_name");
		$uploaded_file = wp_upload_bits( $_FILES[$button_name]['name'], null, file_get_contents( $_FILES[$button_name]['tmp_name'] ) );	
		if ( FALSE === $uploaded_file['error'] ) {
			// Get the data from all those CSVs!
				$data = array();
				$errors = array();
			
				// Get array of CSV files
				$file = $uploaded_file['file'];
							
				// Attempt to change permissions if not readable
				if ( ! is_readable( $file ) ) {
					chmod( $file, 0744 );
				}
		
				// Check if file is writable, then open it in 'read only' mode
				if ( is_readable( $file ) && $_file = fopen( $file, "r" ) ) {
		
					// Get first row in CSV, which is of course the headers
			    	$header = fgetcsv( $_file );
			        while ( $row = fgetcsv( $_file ) ) {
						for ($i = 0; $i < count($header); $i++ ){
				    		$temp[$header[$i]] = $row[$i];
			    		}
			    		$wpdb->insert(
							$table_name,
							$temp
						);
			    		
			        }
					
					fclose( $_file );
		
				} else {
					$errors[] = "File '$file' could not be opened. Check the file's permissions to make sure it's readable by your server.";
				}
			
		
			if ( ! empty( $errors ) ) {
				foreach	($errors as $error){
					//echo $error;
				}
			}			
				
		}
	}
}
