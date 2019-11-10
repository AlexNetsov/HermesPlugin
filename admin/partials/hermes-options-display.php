<?php	if ( ! isset( $_REQUEST['settings-updated'] ) )
          $_REQUEST['settings-updated'] = false; ?>
	<div class="wrap">	 
		
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
               <div class="updated fade"><p><strong><?php _e( 'Options saved!', 'wporg' ); ?></strong></p></div>
          <?php endif; ?>  
	  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	   
		<form method="post" action="options.php">
		  <?php settings_fields( 'hermes_options' ); 
		  do_settings_sections( 'hermes_options' );?>
		  
		  <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Labour rate</th>
	        <td><input type="text" name="hermes_labour_setting" value="<?php echo esc_attr( get_option('hermes_labour_setting') ); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        <th scope="row">X cubics to which the destination pack load price is constant</th>
	        <td><input type="number" name="hermes_to_cubics" value="<?php echo esc_attr( get_option('hermes_to_cubics') ); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        <th scope="row">Labour price for the first X cubic meters</th>
	        <td><input type="number" name="hermes_labour_constant" value="<?php echo esc_attr( get_option('hermes_labour_constant') ); ?>" /></td>
	        </tr>
	        <tr valign="top">
	        <th scope="row">Mail which will recieve entries</th>
	        <td><input type="email" name="hermes_admin_mail" value="<?php echo esc_attr( get_option('hermes_admin_mail') ); ?>" /></td>
	        </tr>
	    </table>
		  <?php submit_button(); ?>
		
		</form>
	   
	</div>