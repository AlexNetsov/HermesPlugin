<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Hermes
 * @subpackage Hermes/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Hermes
 * @subpackage Hermes/admin
 * @author:    Aleksandar Netsov
 */
class Hermes_Public {

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
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hermes_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hermes_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/hermes-public.css', array(), $this->version, 'all' );
	    wp_enqueue_style('font-awesome', plugin_dir_url( __FILE__ ) . 'fonts/font-awesome/css/font-awesome.css'); 


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Hermes_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Hermes_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/hermes-public.js', array( 'jquery' ), $this->version, FALSE );

	}
	function custom_script_enqueuer() {
	   wp_register_script( "wizard_ajax", plugin_dir_url( __FILE__ ) . 'js/wizard-ajax.js', array('jquery'), '1.0', true );
	   wp_localize_script( 'wizard_ajax', 'showRoomsAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
	   wp_enqueue_script( 'wizard_ajax' );
	   
	   wp_register_script( "submit_ajax", plugin_dir_url( __FILE__ ) . 'js/submit-ajax.js', array('jquery'), '1.0', true );
	   wp_localize_script( 'submit_ajax', 'submitValuesAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        
	   wp_enqueue_script( 'submit_ajax' );
	
	}
	
	public function get_submit_values() {
		// Define variables
		global $wpdb;
		$name = sanitize_text_field($_REQUEST['name']);
		$phone = esc_attr($_REQUEST['phone']);
		$email = sanitize_email($_REQUEST['email']);
		$moving_date = sanitize_text_field($_REQUEST['moving_date']);
		$moving_from_address = sanitize_text_field($_REQUEST['moving_from_address']);
		$moving_from_city = sanitize_text_field($_REQUEST['moving_from_city']);
		$moving_to_address = sanitize_text_field($_REQUEST['moving_to_address']);
		$moving_to_city = sanitize_text_field($_REQUEST['moving_to_city']);
		$apartment_type = sanitize_text_field($_REQUEST['apartment_type']);
		$rooms_json = json_decode(stripcslashes($_REQUEST['rooms_json']), true);
		$message_to_client = '<div style="max-width:800px; margin: auto;"><h1>Quote from Matrix Relocations</h1><h3>About you</h3><div><span>Name </span><span>'.$name.'</span></div><div><span>Phone </span><span>'.$phone.'</span></div><div><span>Email </span><span>'.$email.'</span></div><div><span>Moving Date </span><span>'.$moving_date.'</span></div><h3>Moving From</h3><div><span>Address </span><span>'.$moving_from_address.'</span></div><div><span>City </span><span>'.$moving_from_city.'</span></div><h3>Moving To</h3><div><span>Address </span><span>'.$moving_to_address.'</span></div><div><span>City </span><span>'.$moving_to_city.'</span></div>';
		$message_to_admin = '<div style="max-width:800px; margin: auto;"><h1>Quote from Matrix Relocations</h1><div style="width:100%; clear:both; margin: 0 0 10px 0; display: inline-block;"><div style="float:left; width:33.3333%;"><h3>About the client</h3><div><span>Name </span><span>'.$name.'</span></div><div><span>Phone </span><span>'.$phone.'</span></div><div><span>Email </span><span>'.$email.'</span></div><div><span>Moving Date </span><span>'.$moving_date.'</span></div></div><div style="float:left; width:33.3333%;"><h3>Moving From</h3><div><span>Address </span><span>'.$moving_from_address.'</span></div><div><span>City </span><span>'.$moving_from_city.'</span></div></div><div style="float:left; width:33.3333%;"><h3>Moving To</h3><div><span>Address </span><span>'.$moving_to_address.'</span></div><div><span>City </span><span>'.$moving_to_city.'</span></div></div></div>';
		$table_rooms = $wpdb->prefix . 'hms_rooms';
		$table_furniture = $wpdb->prefix . 'hms_furniture';
		$table_orders = $wpdb->prefix . 'hms_orders';
		$moving_from_both = $moving_from_address . ', ' . $moving_from_city;
		$moving_to_both = $moving_to_address . ', ' . $moving_to_city;
		
		// Go through every room and every furniture, compose email messeges
		$total_apartment_cbm = 0;
		$total_apartment_cbf = 0;
		
		$total_material_overseas_price = 0;
		$total_material_euro_price = 0;
		$total_material_blanket_price = 0;
		
		$house_sum_overseas_mats_a_array = [];
		$house_sum_euro_mats_a_array = [];
		$house_sum_blanket_mats_a_array = [];
		
		$house_sum_overseas_mats = [];
		$house_sum_euro_mats = [];
		$house_sum_blanket_mats = [];
		
		error_log('----------------------');
		foreach($rooms_json as $room_id=>$room_json){
			$room_name = $wpdb->get_row("SELECT room_name FROM $table_rooms WHERE room_id = $room_id", ARRAY_A );
			$message_to_client .= '<h3>' . $room_name['room_name'] . '</h3>';
			$message_to_admin .= '<h3>' . $room_name['room_name'] . '</h3>';

			$room_total_cbm = 0;
			$room_total_cbf = 0;
			
			$room_overseas_price = 0;
			$room_euro_price = 0;
			$room_blanket_price = 0;
			
			$room_material_array = '';
			
			$room_sum_overseas_mats = [];
			$room_sum_euro_mats = [];
			$room_sum_blanket_mats = [];
			
			foreach($room_json as $key=>$value){
				$furniture_id =  $value['furniture_id'];
				$furniture_data = $wpdb->get_row("SELECT * FROM $table_furniture WHERE furniture_id = $furniture_id", ARRAY_A );
				
				// Display and calculate cubic meters and feet for single funiture
				$current_furniture_total_cbm = $value['furniture_count']*$furniture_data['cubic_meters'];
				$current_furniture_total_cbf = $value['furniture_count']*$furniture_data['cubic_feet'];
				
				$room_total_cbm += $current_furniture_total_cbm;
				$room_total_cbf += $current_furniture_total_cbf;
				
				$message_to_client .= '<div><span>' . $furniture_data['name_en_us'] . ' </span>';
				$message_to_client .= '<span>' . $value['furniture_count'] . '</span></div>';
				
				$message_to_admin .= '<div><span>' . $furniture_data['name_en_us'] . ' </span>';
				$message_to_admin .= '<span>' . $value['furniture_count'] . '@cbm '.$furniture_data['cubic_meters'].' = ' .$current_furniture_total_cbm . '</span><br>';
				$message_to_admin .= '<span>' . $value['furniture_count'] .'@cbf '. $furniture_data['cubic_feet']. ' = ' .$current_furniture_total_cbf . '</span></div>';
				// End of display and calculate cubic meters and feet for single furniture
				
				// Calculate materials for each packing method
				$current_furniture_overseas_materials = $this->display_and_calculate_materials($furniture_data['overseas_materials']) ;
				$room_overseas_price += $value['furniture_count']*$current_furniture_overseas_materials['sum_price'];
				
				$current_furniture_euro_materials = $this->display_and_calculate_materials($furniture_data['euro_materials']);
				$room_euro_price += $value['furniture_count']*$current_furniture_euro_materials['sum_price'];
				
				$current_furniture_blanket_materials = $this->display_and_calculate_materials($furniture_data['blanket_materials']);
				$room_blanket_price += $value['furniture_count']*$current_furniture_blanket_materials['sum_price'];
				
				// Compile arrays of materials for every method by room
				foreach($current_furniture_overseas_materials as $key=>$material){
					if(is_numeric($key)){
						if(array_key_exists($key, $room_sum_overseas_mats)){
							$room_sum_overseas_mats[$key]['pack_count'] += $material['pack_count'];
						}
						else {
							$room_sum_overseas_mats[$key] = $material;
						}
					}
				}
				foreach($current_furniture_euro_materials as $key=>$material){
					if(is_numeric($key)){
						if(array_key_exists($key, $room_sum_euro_mats)){
							$room_sum_euro_mats[$key]['pack_count'] += $material['pack_count'];
						}
						else {
							$room_sum_euro_mats[$key] = $material;
						}
					}
				}
				foreach($current_furniture_blanket_materials as $key=>$material){
					if(is_numeric($key)){
						if(array_key_exists($key, $room_sum_blanket_mats)){
							$room_sum_blanket_mats[$key]['pack_count'] += $material['pack_count'];
						}
						else {
							$room_sum_blanket_mats[$key] = $material;
						}
					}
				}
				

			} // End furniture foreach
			
			$total_apartment_cbm += $room_total_cbm;
			$total_apartment_cbf += $room_total_cbf;
			
			$total_material_overseas_price += $room_overseas_price;
			$total_material_euro_price += $room_euro_price;
			$total_material_blanket_price += $room_blanket_price;
			
			$room_overseas_mats_string = '';
			$room_euro_mats_string = '';
			$room_blanket_mats_string = '';
			
			
			
			foreach($room_sum_overseas_mats as $mat){
				$room_overseas_mats_string .= '<span>'.$mat['pack_count'].' '.$mat['pack_entity'].' of '.$mat['pack_name'].', </span>';
				if(array_key_exists($mat['pack_name'], $house_sum_overseas_mats)){
					$house_sum_overseas_mats[$mat['pack_name']]['pack_count'] += $mat['pack_count'];
				}
				else {
					$house_sum_overseas_mats[$mat['pack_name']] = $mat;
				}
			}
			
			foreach($room_sum_euro_mats as $mat){
				$room_euro_mats_string .= '<span>'.$mat['pack_count'].' '.$mat['pack_entity'].' of '.$mat['pack_name'].', </span>';
				if(array_key_exists($mat['pack_name'], $house_sum_euro_mats)){
					$house_sum_euro_mats[$mat['pack_name']]['pack_count'] += $mat['pack_count'];
				}
				else {
					$house_sum_euro_mats[$mat['pack_name']] = $mat;
				}
			}
			
			foreach($room_sum_blanket_mats as $mat){
				$room_blanket_mats_string .= '<span>'.$mat['pack_count'].' '.$mat['pack_entity'].' of '.$mat['pack_name'].', </span>';
				if(array_key_exists($mat['pack_name'], $house_sum_blanket_mats)){
					$house_sum_blanket_mats[$mat['pack_name']]['pack_count'] += $mat['pack_count'];
				}
				else {
					$house_sum_blanket_mats[$mat['pack_name']] = $mat;
				}
			}
			
			$house_sum_overseas_mats_a_array[$room_id] = $room_sum_overseas_mats;
			$house_sum_euro_mats_a_array[$room_id] = $room_sum_euro_mats;
			$house_sum_blanket_mats_a_array[$room_id] = $room_sum_blanket_mats;
			
			$message_to_admin .= '<br>';
			$message_to_admin .= '<div>Room total cbm: '.$room_total_cbm.'</div>';
			$message_to_admin .= '<div>Room total cbf: '.$room_total_cbf.'</div>';
			$message_to_admin .= '<div>Room overseas wrap price: '.$room_overseas_price.'</div>';
			$message_to_admin .= '<div>Overseas wrap materials: '.$room_overseas_mats_string.'</div>';
			$message_to_admin .= '<div>Room eurowrap price: '.$room_euro_price.'</div>';
			$message_to_admin .= '<div>Eurowrap materials: '.$room_euro_mats_string.'</div>';
			$message_to_admin .= '<div>Room blanketwrap price: '.$room_blanket_price.'</div>';
			$message_to_admin .= '<div>Blanket materials: '.$room_blanket_mats_string.'</div>';

		} //End rooms foreach
		
		$message_to_admin .= '<br>';
		$message_to_admin .= '<div style="width: 100%; display: inline-block; clear: both;"><h3>House total</h3>';
		$message_to_admin .= '<div>House total cbm: '.$total_apartment_cbm.'</div>';
		$message_to_admin .= '<div>House total cbf: '.$total_apartment_cbf.'</div>';
		$message_to_admin .= '<div>House overseas wrap price: '.$total_material_overseas_price.'</div>';
		$message_to_admin .= '<div>House overseas total materials:';
		
		foreach($house_sum_overseas_mats as $single_house_mat){
			$message_to_admin .= '<span>'.$single_house_mat['pack_count'].' '.$single_house_mat['pack_entity'].' of '.$single_house_mat['pack_name'].', </span>';
		}
		
		$message_to_admin .= '</div>';
		$message_to_admin .= '<div>House eurowrap price: '.$total_material_euro_price.'</div>';
		$message_to_admin .= '<div>House eurowrap total materials:';
		
		foreach($house_sum_euro_mats as $single_house_mat){
			$message_to_admin .= '<span>'.$single_house_mat['pack_count'].' '.$single_house_mat['pack_entity'].' of '.$single_house_mat['pack_name'].', </span>';
		}
		
		$message_to_admin .= '</div>';
		$message_to_admin .= '<div>House blanketwrap price: '.$total_material_blanket_price.'</div>';
		$message_to_admin .= '<div>House blanketwrap total materials:';
		
		foreach($house_sum_blanket_mats as $single_house_mat){
			$message_to_admin .= '<span>'.$single_house_mat['pack_count'].' '.$single_house_mat['pack_entity'].' of '.$single_house_mat['pack_name'].', </span>';
		}
		
		$message_to_admin .= '</div>';
		
		// Calculate origin and destination time, price and packers
		$total_apartment_cbm = round($total_apartment_cbm);
		
		$table_labour = $wpdb->prefix . 'hms_labour';
		$labour_data = $wpdb->get_row("SELECT * FROM $table_labour WHERE volume_cubic_meters = $total_apartment_cbm", ARRAY_A );
		$labour_per_hour_price = get_option('hermes_labour_setting');
		$cubics_to_same_price = get_option('hermes_to_cubics');
		$labour_constant = get_option('hermes_labour_constant');
		
		if ($total_apartment_cbm <= $cubics_to_same_price){
			$overseas_destination_pack_load = $labour_constant;
			$euro_destination_pack_load = $labour_constant;
			$blanket_destination_pack_load = $labour_constant;
		}
		else {
			$overseas_destination_pack_load = round($labour_per_hour_price*$labour_data['overseas_destination_hours'], 2);
			$euro_destination_pack_load = round($labour_per_hour_price*$labour_data['european_destination_hours'], 2);
			$blanket_destination_pack_load = round($labour_per_hour_price*$labour_data['blanketwrap_destination_hours'], 2);
		}
		
		$overseas_origin_pack_load = round($labour_per_hour_price*$labour_data['overseas_origin_hours'], 2);
		$euro_origin_pack_load = round($labour_per_hour_price*$labour_data['european_origin_hours'], 2);
		$blanket_origin_pack_load = round($labour_per_hour_price*$labour_data['blanketwrap_origin_hours'], 2);
		
		$message_to_admin .= '<div style="float:left; width: 33.3333%;"><h4>Overseas</h4>';
		$message_to_admin .= '<div>Origin packers: '.round($labour_data['overseas_origin_packers']).'</div>';
		$message_to_admin .= '<div>Origin hours: '.$labour_data['overseas_origin_hours'].'</div>';
		$message_to_admin .= '<div>Origin pack load: '. $overseas_origin_pack_load .'</div>';

		$message_to_admin .= '<div>Destination packers: '.round($labour_data['overseas_destination_packers']).'</div>';
		$message_to_admin .= '<div>Destination hours: '.$labour_data['overseas_destination_hours'].'</div>';
		$message_to_admin .= '<div>Destination pack load: '. $overseas_destination_pack_load .'</div></div>';

		$message_to_admin .= '<div style="float:left; width: 33.3333%;"><h4>Euro wrap</h4>';
		$message_to_admin .= '<div>Origin packers: '.round($labour_data['european_origin_packers']).'</div>';
		$message_to_admin .= '<div>Origin hours: '.$labour_data['european_origin_hours'].'</div>';
		$message_to_admin .= '<div>Origin pack load: '. $euro_origin_pack_load .'</div>';
		
		$message_to_admin .= '<div>Destination packers: '.round($labour_data['european_destination_packers']).'</div>';
		$message_to_admin .= '<div>Destination hours: '.$labour_data['european_destination_hours'].'</div>';
		$message_to_admin .= '<div>Destination pack load: '. $euro_destination_pack_load .'</div></div>';
		
		$message_to_admin .= '<div style="float:left; width: 33.3333%;"><h4>Blanket wrap</h4>';
		$message_to_admin .= '<div>Origin packers: '.round($labour_data['blanketwrap_origin_packers']).'</div>';
		$message_to_admin .= '<div>Origin hours: '.$labour_data['blanketwrap_origin_hours'].'</div>';
		$message_to_admin .= '<div>Origin pack load: '. $blanket_origin_pack_load .'</div>';

		$message_to_admin .= '<div>Destination packers: '.round($labour_data['blanketwrap_destination_packers']).'</div>';
		$message_to_admin .= '<div>Destination hours: '.$labour_data['blanketwrap_destination_hours'].'</div>';
		$message_to_admin .= '<div>Destination pack load: '. $blanket_destination_pack_load .'</div></div></div>';

		
		// Price in order is origin pack load price+ destination pack load price + material price?

		$total_overseas_price = $overseas_origin_pack_load + $overseas_destination_pack_load + $total_material_overseas_price;
		$total_euro_price = $euro_origin_pack_load + $euro_destination_pack_load + $total_material_euro_price;
		$total_blanket_price = $blanket_origin_pack_load + $blanket_destination_pack_load + $total_material_blanket_price;
		
		
		$message_to_client .= '</div>';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		if ( wp_mail( $email, 'Your quote from Matrix Relocations', $message_to_client, $headers ) ) {
			$response['mail'] = 'success';
		} else {
			$response['mail'] = 'failure';
		}
		
		$admin_mail = get_option('hermes_admin_mail');
		
		if ( wp_mail( $admin_mail, 'New entry in Matrix Relocations', $message_to_admin, $headers ) ) {
			$response['mail_to_admin'] = 'success';
		} else {
			$response['mail_to_admin'] = 'failure';
		}
 
		// Make the order query
		$order_success = $wpdb->insert(
			$table_orders,
			array(
				'orderer_name' => $name,
				'orderer_phone' => $phone,
				'orderer_email' => $email,
				'moving_date' => $moving_date,
				'moving_from' => $moving_from_both,
				'moving_to' => $moving_to_both,
				'house_type' => $apartment_type,
				'rooms_furniture' => serialize($room_json),
				'total_cubic_feet' => $total_apartment_cbf,
				'total_cubic_meters' => $total_apartment_cbm,
				'overseas_price' => $total_overseas_price,
				'overseas_mats' => serialize($house_sum_overseas_mats_a_array),
				'euro_price' => $total_euro_price,
				'euro_mats' => serialize($house_sum_euro_mats_a_array),
				'blanket_price' => $total_blanket_price,
				'blanket_mats' => serialize($house_sum_blanket_mats_a_array),
				'overseas_total_origin_time' => $labour_data['overseas_origin_hours'],
				'overseas_total_destination_time' => $labour_data['overseas_destination_hours'],
				'euro_total_origin_time' => $labour_data['european_origin_hours'],
				'euro_total_destination_time' => $labour_data['european_destination_hours'],
				'blanket_total_origin_time' => $labour_data['blanketwrap_origin_hours'],
				'blanket_total_destination_time' => $labour_data['blanketwrap_destination_hours']
			)
		);
		
		$response['message'] = $message_to_admin;
		if ($order_success){
			$response['type'] = 'success';
		}
		else {
			$response['type'] = 'failed';
			if (empty($name)) {
				$response['client_name'] = 'missing';
			}
			if (empty($phone)) {
				$response['client_phone'] = 'missing';
			}
			if (empty($email)) {
				$response['client_email'] = 'missing';
			}
			if (empty($moving_date)) {
				$response['client_moving_date'] = 'missing';
			}
			if (empty($moving_from_address)) {
				$response['client_moving_from_address'] = 'missing';
			}
			if (empty($moving_from_city)) {
				$response['client_moving_from_city'] = 'missing';
			}
			if (empty($moving_to_address)) {
				$response['client_moving_to_address'] = 'missing';
			}
			if (empty($moving_to_city)) {
				$response['client_moving_to_city'] = 'missing';
			}
			if ($apartment_type == 0) {
				$response['client_apartment_type'] = 'missing';
			}
		}
    

		echo  json_encode($response);
		die();
	}
	public function display_and_calculate_materials( $mats_array ){
		global $wpdb;
		$return_array = '';
		$price = 0;
		$table_materials = $wpdb->prefix . 'hms_packing_materials';
		$mats_array = explode('; ', $mats_array);
		$mats_array = array_diff( $mats_array, array( '' ) );
		foreach($mats_array as $single_material){
			$single_material = explode(':', $single_material);
			$mat_id = $single_material[0];
			$mat_count = $single_material[1];
			$single_mat_data = $wpdb->get_row("SELECT * FROM $table_materials WHERE pack_id = $mat_id", ARRAY_A );
			
			$price += $mat_count * $single_mat_data['pack_price'];
			$return_array[$single_mat_data['pack_id']] = array('pack_name' => $single_mat_data['pack_name'], 'pack_entity' => $single_mat_data['pack_entity'], 'pack_count' => $mat_count);
		}
		$return_array['sum_price'] = $price;
		
		return $return_array;
	}
	
	
	public function hms_show_rooms() {
		global $wpdb;
		$id = $_REQUEST['id'];
		$add_rooms = $_REQUEST['add_rooms'];
		// PHP 7 fixes
		//$response = [];
		// End of PHP 7 fixes
		$response = [];
		$response['additionalRooms_furniture'] = '';
		$response['furniture'] = [];
		$response['rooms'] = '';

		if ( $add_rooms == 1){
			$table_name = $wpdb->prefix . 'hms_rooms';
			$additional_rooms = $wpdb->get_results( "SELECT room_name, room_id FROM $table_name WHERE house_type = 22", ARRAY_A );
			$table_name_single_room = $wpdb->prefix . 'hms_furniture';
			foreach($additional_rooms as $additional_room){
				$room_id = $additional_room['room_id'];
				$room_furniture_array = $wpdb->get_results("SELECT name_en_us, furniture_id FROM $table_name_single_room WHERE room_id = $room_id", ARRAY_A);
				
				
				
				foreach($room_furniture_array as $room_single_furniture){
					$response['additionalRooms_furniture'][$room_id] .= $room_single_furniture['name_en_us'] . '-'. $room_single_furniture['furniture_id'] . ',';
				}
				$response['additionalRooms'] .= $additional_room['room_name'] . '-' .$additional_room['room_id'] . ',';
				
			}
		}
		$table_name = $wpdb->prefix . 'hms_rooms';
		$apartment_rooms = $wpdb->get_results( "SELECT room_name, room_id FROM $table_name WHERE house_type = $id", ARRAY_A );
		$table_name_single_room = $wpdb->prefix . 'hms_furniture';
		// PHP 7 fixes
		//$response['furniture'] = [];
		//$response['rooms'] = [];
		// End of PHP 7 fixes
		foreach($apartment_rooms as $apartment_room){
			$room_id = $apartment_room['room_id'];
			$room_furniture_array = $wpdb->get_results("SELECT name_en_us, furniture_id FROM $table_name_single_room WHERE room_id = $room_id", ARRAY_A);
			
			foreach($room_furniture_array as $room_single_furniture){
				if(!isset($response['furniture'][$room_id])) {
					$response['furniture'][$room_id] = '';
				}
				$response['furniture'][$room_id] .= $room_single_furniture['name_en_us'] . '-'. $room_single_furniture['furniture_id'] . ',';
			}
			$response['rooms'] .= $apartment_room['room_name'] . '-' .$apartment_room['room_id'] . ',';
			
		}
		$response['type'] = 'success' ;
    

		echo  json_encode($response);
		die();
		
	}
	
	// Register hermes shortcode
	public function hermes_shortcode() {
		global $wpdb;
		$heading = esc_html__('Please take your time and fill in the form below. Your accuracy is crucial in obtaining the right estimate and having a smooth relocation.','hermes');
		$about_you = esc_html__('About you','hermes');
		$moving_from = esc_html__('Moving from','hermes');
		$moving_to = esc_html__('Moving to','hermes');	
		$moving_date = esc_html__('Approximate moving date','hermes');	
		$table_name = $wpdb->prefix . 'hms_house_types';
		$apartment_type_options = $wpdb->get_results( "SELECT house_type_name FROM $table_name",  ARRAY_A);
		$apartment_type_options_html = '<option value="0">Select apartment type</option>';
		$value_number = 1;
		foreach($apartment_type_options as $apartment_type_option){
			$apartment_type_options_html .= '<option value="'.$value_number.'">'.$apartment_type_option['house_type_name'].'</option>';
			$value_number++;
		}
		echo '<form action="'.esc_url( $_SERVER['REQUEST_URI'] ).'" method="post" accept-charset="utf-8" class="hermes_form">
					<h3>'.$heading.'</h3>
					<h4>'.$about_you.'</h4>
					
					<input id="hms-name" type="text" name="hms-name" placeholder="Your name" />
					
					<input id="hms-phone" type="text" name="hms-phone" pattern="/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/" placeholder="Telephone" />

					<input id="hms-email" type="email" name="hms-email" placeholder="Your email" />
					
					<h4>'.$moving_from.'</h4>
					<input id="hms-moving-from-address" pattern="\d{1,5}\s\w.\s(\b\w*\b\s){1,2}\w*\." type="text" name="hms-moving-from-address" placeholder="What is your current address?" />
					<input id="hms-moving-from-city" type="text" name="hms-moving-from-city" placeholder="Which city are you moving from?" />
					
					<h4>'.$moving_to.'</h4>
					<input id="hms-moving-to-address" type="text" name="hms-moving-to-address" placeholder="Which will be your new address?" />
					<input id="hms-moving-to-city" type="text" name="hms-moving-to-city" placeholder="Which city are we going to move you to?" />
					
					<div id="hms-moving-date-container">
						<h4>' .$moving_date. '</h4>
						<input id="hms-moving-date" type="date" name="hms-moving-date" placeholder="Approximate moving date" />
					</div>
					<div id="hms-apartment-type-container">
						<h4>What is your apartment type?</h4>
						<select id="hms-apartment-type" name="hms-apartment-type">'.$apartment_type_options_html.'</select>
					</div>
					<div class="hms-add-rooms"></div>
					<div class="add-rooms-container"></div>
					<div class="hms-rooms-container"></div>
					<br>
					<button type="button" name="hms-request-quote" id="hms-request-quote">Request a quote</button>
				</form>';
	}
	
	public function register_hermes_shortcode() {
		add_shortcode( 'hermes', array( $this, 'hermes_shortcode' ) );
	}
	public function preloader_script() {
		global $post;
		if( has_shortcode( $post->post_content, 'hermes') ) {
			wp_register_script( "preloader", plugin_dir_url( __FILE__ ) . 'js/preloader.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'preloader');
		}
	}
}