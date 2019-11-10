(function( $ ) {
	$('#hms-request-quote').on('click', function(){
		var name = $('#hms-name').val();
		var phone = $('#hms-phone').val();
		var email = $('#hms-email').val();
		var moving_date = $('#hms-moving-date').val();
		var moving_from_address = $('#hms-moving-from-address').val();
		var moving_from_city = $('#hms-moving-from-city').val();
		var moving_to_address = $('#hms-moving-to-address').val();
		var moving_to_city = $('#hms-moving-to-city').val();
		var apartment_type = $('#hms-apartment-type').find(':selected').html();
		var room_id = '';
		var furniture_id = '';
		var furniture_count = '';
		var furniture_object = {};
		var furniture_array = [];
		var rooms_array = {};
		var rooms_json = '';
		
		// Remove previosly market as error fields
		$('#hms-name').removeClass('error-field');
		$('#hms-phone').removeClass('error-field');
		$('#hms-email').removeClass('error-field');
		$('#hms-moving-date').removeClass('error-field');
		$('#hms-moving-from-address').removeClass('error-field');
		$('#hms-moving-from-city').removeClass('error-field');
		$('#hms-moving-to-address').removeClass('error-field');
		$('#hms-moving-to-city').removeClass('error-field');
		
		// Get all the furnitures by rooms into JSON string
		$('.hms-rooms-container .single-room').each(function(){
			room_id = $(this).attr('room-id');
			furniture_array = [];
			$(this).find('.selected-furniture').each(function(){
				furniture_id = $(this).attr('furniture-id');
				furniture_count = $(this).find('#selected-furniture-count').val();
				furniture_object = {"furniture_id" : furniture_id, "furniture_count" :furniture_count};				
				furniture_array.push(furniture_object);
			});
			rooms_array[room_id] = furniture_array;
		});
		
		$('.add-rooms-container .additional-single-room[style="display: block;"]').each(function(){
			room_id = $(this).attr('room-id');
			furniture_array = [];
			$(this).find('.selected-furniture').each(function(){
				furniture_id = $(this).attr('furniture-id');
				furniture_count = $(this).find('#selected-furniture-count').val();
				furniture_object = {"furniture_id" : furniture_id, "furniture_count" :furniture_count};				
				furniture_array.push(furniture_object);
			});
			rooms_array[room_id] = furniture_array;
		});
		
		rooms_json = JSON.stringify(rooms_array);
		jQuery.ajax({
         type : "post",
         dataType : "json",
         url : submitValuesAjax.ajaxurl,
         data : {action: "get_submit_values", name : name, phone : phone, email : email, moving_date : moving_date, moving_from_address : moving_from_address, moving_from_city : moving_from_city, moving_to_address : moving_to_address, moving_to_city : moving_to_city, apartment_type : apartment_type, rooms_json : rooms_json},
         success: function(response) {
            if(response.type == "success" ) {
	           if (response.mail == "success"){
		           var wnd = window.open("about:blank", "", "_blank");
				   wnd.document.write(response.message);
				   $('.hermes_form').closest('div').html('<div class="hermes_thankyou"><h2>Thank you for your quote!</h2><h4>We will get back to you shortly</h4></div>');
	           }
	           else {
		           $('.hermes_failed').remove();
		           $('.hermes_form').append('<h3 class="hermes_failed">Sending email failed. Please check the email you entered.</h3>');
		           }
	            }
	            else {
		            $('.hermes_failed').remove();
		            
					if (response.client_name == 'missing') {
						$('#hms-name').addClass('error-field');
					}
					if (response.client_phone == 'missing') {
						$('#hms-phone').addClass('error-field');
					}
					if (response.client_email == 'missing') {
						$('#hms-email').addClass('error-field');
					}
					if (response.client_moving_date == 'missing') {
						$('#hms-moving-date').addClass('error-field');
					}
					if (response.client_moving_from_address == 'missing') {
						$('#hms-moving-from-address').addClass('error-field');
					}
					if (response.client_moving_from_city == 'missing') {
						$('#hms-moving-from-city').addClass('error-field');
					}
					if (response.client_moving_to_address == 'missing') {
						$('#hms-moving-to-address').addClass('error-field');
					}
					if (response.client_moving_to_city == 'missing') {
						$('#hms-moving-to-city').addClass('error-field');
					}
					if (response.client_apartment_type == 'missing') {
						$('#hms-apartment-type').addClass('error-field');
					} 
	            
					$('.hermes_form').append('<h3 class="hermes_failed">Your request couldn\'t be handled, please check the marked fields and try again.</h3>');

            }
         }
	       	
	   })
	});
})( jQuery );