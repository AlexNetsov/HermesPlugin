<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Hermes
 * @subpackage Hermes/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Hermes
 * @subpackage Hermes/includes
 * @author:    Aleksandar Netsov
 */
class Hermes_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $hermes_db_version;
		$hermes_db_version = '1.0';
	
		global $wpdb;
		global $hermes_db_version;
		
		// create hms_rooms 
		$table_name = $wpdb->prefix . 'hms_rooms';
		
		$charset_collate = $wpdb->get_charset_collate();
	
		$sql = "CREATE TABLE $table_name (
			row_id mediumint(9) NOT NULL AUTO_INCREMENT,
			room_id mediumint(9) NOT NULL,
			room_name text NOT NULL,
			house_type text NOT NULL,
			UNIQUE KEY row_id (row_id)
		) $charset_collate;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		// create hms_house_types
		$table_name = $wpdb->prefix . 'hms_house_types';
		
		$sql = "CREATE TABLE $table_name (
			house_type_id mediumint(9) NOT NULL AUTO_INCREMENT,
			house_type_name text NOT NULL,
			UNIQUE KEY house_type_id (house_type_id)
		) $charset_collate;";
	
		dbDelta( $sql );
		
		//create hms_packing_materials
		$table_name = $wpdb->prefix . 'hms_packing_materials';
		
		$sql = "CREATE TABLE $table_name (
			pack_id mediumint(9) NOT NULL AUTO_INCREMENT,
			pack_name text NOT NULL,
			pack_price decimal(10,3) NOT NULL,
			pack_entity text NOT NULL,
			UNIQUE KEY pack_id (pack_id)
		) $charset_collate;";
	
		dbDelta( $sql );
		
		//create hms_orders
		$table_name = $wpdb->prefix . 'hms_orders';
		
		$sql = "CREATE TABLE $table_name (
			order_id int NOT NULL AUTO_INCREMENT,
			orderer_name text NOT NULL,
			orderer_phone text NOT NULL,
			orderer_email tinytext NOT NULL,
			moving_date date NOT NULL,
			moving_from text NOT NULL,
			moving_to text NOT NULL,
			house_type text NOT NULL,
			rooms_furniture text NOT NULL,
			total_cubic_feet mediumint(9) NOT NULL,
			total_cubic_meters mediumint(9) NOT NULL,
			overseas_price decimal(10,3) NOT NULL,
			overseas_mats text NOT NULL,
			euro_price decimal(10,3) NOT NULL,
			euro_mats text NOT NULL,
			blanket_price decimal(10,3) NOT NULL,
			blanket_mats text NOT NULL,
			overseas_total_origin_time decimal (30,2) NOT NULL,
			overseas_total_destination_time decimal (30,2) NOT NULL,
			euro_total_origin_time decimal (30,2) NOT NULL,
			euro_total_destination_time decimal (30,2) NOT NULL,
			blanket_total_origin_time decimal (30,2) NOT NULL,
			blanket_total_destination_time decimal (30,2) NOT NULL,
			UNIQUE KEY order_id (order_id)
		) $charset_collate;";
	
		dbDelta( $sql );
		
		//create hms_furniture
		$table_name = $wpdb->prefix . 'hms_furniture';
		
		$sql = "CREATE TABLE $table_name (
			furniture_id int NOT NULL AUTO_INCREMENT,
			name_en_US text NOT NULL,
			room_id mediumint(9) NOT NULL,
			measurements_feet text NOT NULL,
			cubic_feet decimal(10,2) NOT NULL,
			measurements_centimeters text NOT NULL,
			cubic_meters decimal(10,2) NOT NULL,
			overseas_materials text NOT NULL,
			euro_materials text NOT NULL,
			blanket_materials text NOT NULL,
			UNIQUE KEY furniture_id (furniture_id)
		) $charset_collate;";
	
		dbDelta( $sql );
		
		//create hms_labour
		$table_name = $wpdb->prefix . 'hms_labour';
		
		$sql = "CREATE TABLE $table_name (
			volume_cubic_meters int NOT NULL AUTO_INCREMENT,
			overseas_origin_packers decimal(5,2) NOT NULL,
			overseas_origin_hours decimal(5,2) NOT NULL,
			overseas_origin_unit_price decimal(5,2) NOT NULL,
			overseas_origin_pack_load decimal(5,2) NOT NULL,
			overseas_destination_packers decimal(5,2) NOT NULL,
			overseas_destination_hours decimal(5,2) NOT NULL,
			overseas_destination_unit_price decimal(5,2) NOT NULL,
			overseas_destination_pack_load decimal(5,2) NOT NULL,
			european_origin_packers decimal(5,2) NOT NULL,
			european_origin_hours decimal(5,2) NOT NULL,
			european_origin_unit_price decimal(5,2) NOT NULL,
			european_origin_pack_load decimal(5,2) NOT NULL,
			european_destination_packers decimal(5,2) NOT NULL,
			european_destination_hours decimal(5,2) NOT NULL,
			european_destination_unit_price decimal(5,2) NOT NULL,
			european_destination_pack_load decimal(5,2) NOT NULL,
			blanketwrap_origin_packers decimal(5,2) NOT NULL,
			blanketwrap_origin_hours decimal(5,2) NOT NULL,
			blanketwrap_origin_unit_price decimal(5,2) NOT NULL,
			blanketwrap_origin_pack_load decimal(5,2) NOT NULL,
			blanketwrap_destination_packers decimal(5,2) NOT NULL,
			blanketwrap_destination_hours decimal(5,2) NOT NULL,
			blanketwrap_destination_unit_price decimal(5,2) NOT NULL,
			blanketwrap_destination_pack_load decimal(5,2) NOT NULL,
			UNIQUE KEY volume_cubic_meters (volume_cubic_meters)
		) $charset_collate;";
	
		dbDelta( $sql );
	
		add_option( 'hermes_db_version', $hermes_db_version );	
	}	
}
?>