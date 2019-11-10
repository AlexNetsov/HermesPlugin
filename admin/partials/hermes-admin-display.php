<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Hermes
 * @subpackage Hermes/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h2>Hermes Upload CSVs</h2>
    
    <form action="<?php echo"{$_SERVER['REQUEST_URI']}" ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	    <label for="csv-rooms-upload">Select File To Upload for Rooms</label>
		<input type="file" id="csv-rooms-upload" name="csv-rooms-upload" value="" /><?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-rooms-upload-nonce' ); ?>
		<input type="text" value="insert_hermes_rooms" name="insert_hermes_rooms" hidden="hidden" />
		 <input type="submit" name="upload" value="Upload">
    </form>
    
	<form action="<?php echo"{$_SERVER['REQUEST_URI']}" ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	    <label for="csv-house-types-upload">Select File To Upload for House Types</label>
		<input type="file" id="csv-house-types-upload" name="csv-house-types-upload" value="" /><?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-house-types-upload-nonce' ); ?>
		<input type="text" value="insert_hermes_house_types" name="insert_hermes_house_types" hidden="hidden" />
		 <input type="submit" name="upload" value="Upload">
    </form>	
    
    <form action="<?php echo"{$_SERVER['REQUEST_URI']}" ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	    <label for="csv-labour-upload">Select File To Upload for Labour</label>
		<input type="file" id="csv-labour-upload" name="csv-labour-upload" value="" /><?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-labour-upload-nonce' ); ?>
			<input type="text" value="insert_hermes_labour" name="insert_hermes_labour" hidden="hidden" />
		 <input type="submit" name="upload" value="Upload">
    </form>	
    
    <form action="<?php echo"{$_SERVER['REQUEST_URI']}" ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	    <label for="csv-furniture-upload">Select File To Upload for Furniture</label>
		<input type="file" id="csv-furniture-upload" name="csv-furniture-upload" value="" /><?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-furniture-upload-nonce' ); ?>
			<input type="text" value="insert_hermes_furniture" name="insert_hermes_furniture" hidden="hidden" />
		 <input type="submit" name="upload" value="Upload">
    </form>	
    
    <form action="<?php echo"{$_SERVER['REQUEST_URI']}" ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	    <label for="csv-packing-materials-upload">Select File To Upload for Packing Materials</label>
		<input type="file" id="csv-packing-materials-upload" name="csv-packing-materials-upload" value="" /><?php wp_nonce_field( plugin_basename( __FILE__ ), 'csv-packing-materials-upload-nonce' ); ?>
			<input type="text" value="insert_hermes_packing_materials" name="insert_hermes_packing_materials" hidden="hidden" />
		 <input type="submit" name="upload" value="Upload">
    </form>	

</div>