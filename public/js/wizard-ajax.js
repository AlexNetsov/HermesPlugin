(function( $ ) {
$('#hms-apartment-type').one( "change", function() {
	$('.hms-add-rooms').html('<p>Add more rooms:</p><select id="add-more-rooms"></select><i class="fa fa-plus add-rooms-button" aria-hidden="true"></i>');
});
var add_rooms = 1;
$('#hms-apartment-type').change(function() {
	 var id = $(this).find(':selected').val();
	 if (id	==	0){
		 $('.hms-add-rooms').hide();
		 $('.hms-rooms-container').empty();
	 }
	 else {
		 $('.hms-add-rooms').show();
	 }
     jQuery.ajax({
         type : "post",
         dataType : "json",
         url : showRoomsAjax.ajaxurl,
         data : {action: "hms_show_rooms", id : id, add_rooms : add_rooms},
         success: function(response) {
            if(response.type == "success") {
	            if ( add_rooms == 1){
					var additional_rooms = response.additionalRooms.split(',').filter(function(v){return v!==''});
					var additional_rooms_html = '';
					var all_single_roomsHtml = '';
					additional_rooms.forEach(function(item){
						var add_furnitureValues = '';
						var addRoomDetails = item.split('-');
						additional_rooms_html += '<option value="'+addRoomDetails[1]+'">'+addRoomDetails[0]+'</option>';
					response.additionalRooms_furniture[addRoomDetails[1]].split(',').filter(function(v){return v!==''}).forEach(function(item){
			            var additional_singleFurniture = item.split('-');
			            add_furnitureValues += '<option value="'+additional_singleFurniture[1]+'">'+additional_singleFurniture[0]+'</option>';
		            });
		           all_single_roomsHtml += '<div class="additional-single-room" room-id="'+ addRoomDetails[1] +'">' + '<div class="header-container"><h3>' + addRoomDetails[0] + '</h3><i class="fa fa-minus remove-room" room-id="'+ addRoomDetails[1] +'"></i></div>' + '<div id="single-furniture-container" room-id="'+ addRoomDetails[1] +'"><select id="room-furnitures" room-id="'+ addRoomDetails[1] +'">' + add_furnitureValues + '</select><input type="number" min="1" id="furniture-count" value="1" name="furniture-count" />' + '<i class="fa fa-plus add-furniture" room-id="'+ addRoomDetails[1] +'"></i></div>' + '</div>';
					});
					$('#add-more-rooms').html(additional_rooms_html);
					$('.add-rooms-container').html(all_single_roomsHtml);
					
					add_rooms = 0;
	            }
	           var roomsHtml = '';
	           var rooms = response.rooms.split(',').filter(function(v){return v!==''});
	           rooms.forEach(function(item){
		           var furnitureValues = '';
		           var roomDetails = item.split('-');
		           
		           response.furniture[roomDetails[1]].split(',').filter(function(v){return v!==''}).forEach(function(item){
			           var singleFurniture = item.split('-');
			           furnitureValues += '<option value="'+singleFurniture[1]+'">'+singleFurniture[0]+'</option>';
		           });
		           roomsHtml += '<div class="single-room" room-id="'+ roomDetails[1] +'">' + '<h3>' + roomDetails[0] + '</h3>' + '<div id="single-furniture-container" room-id="'+ roomDetails[1] +'"><select id="room-furnitures" room-id="'+ roomDetails[1] +'">' + furnitureValues + '</select><input type="number" min="1" id="furniture-count" value="1" name="furniture-count" />' + '<i class="fa fa-plus add-furniture" room-id="'+ roomDetails[1] +'"></i></div>' + '</div>';
		           $('.hms-rooms-container').html(roomsHtml);
	           });
               console.log('gg wp');
            }
            else {
               alert("Sad");
            }
         }
	       	
	   });

 });
 
 $(document).on('click', '.add-furniture', function(){
	 var room_id = $(this).attr('room-id');
	 var furniture_count = $('#single-furniture-container[room-id="' + room_id + '"] input').val();
	 var furniture_id = $('#room-furnitures[room-id="' + room_id + '"]').find(':selected').val();
	 var furniture_name = $('#room-furnitures[room-id="' + room_id + '"]').find(':selected').html();
	 $('#single-furniture-container[room-id="' + room_id + '"]').before('<div class="selected-furniture" furniture-id="' + furniture_id + '"><span class="selected-furniture-name">' + furniture_name + '</span><input type="number" value="' + furniture_count + '" min="1" furniture-id="' + furniture_id + '" id="selected-furniture-count" name="selected-furniture-count" /><i class="fa fa-minus remove-furniture" room-id="'+ room_id +'" furniture-id="' + furniture_id + '"></i></div>');
	 $('#room-furnitures[room-id="' + room_id + '"]').find(':selected').detach();
	 if ($('#room-furnitures[room-id="' + room_id + '"]').is(':empty')){
		$('#single-furniture-container[room-id="' + room_id + '"]').hide();
	 }
 });
 
 $(document).on('click', '.add-rooms-button', function(){
	 var add_room_id = $('select#add-more-rooms').find(':selected').val();
	 $('select#add-more-rooms option[value="'+add_room_id+'"]').detach();
	 $('.additional-single-room[room-id="' + add_room_id + '"').show();
	 if ($('select#add-more-rooms').is(':empty')){
		 $('.hms-add-rooms').hide();
	 }

 }); 
  $(document).on('click', '.remove-room', function(){
	 var remove_room_id = $(this).attr('room-id');
	 var remove_room_name = $(this).siblings('h3').html();
	 $('.additional-single-room[room-id="' + remove_room_id + '"').hide();
	 $('select#add-more-rooms').append('<option value="' + remove_room_id + '">' + remove_room_name + '</option>');
	 $('.hms-add-rooms').show();
 });
 $(document).on('click', '.remove-furniture', function(){
	 var remove_furniture_room_id = $(this).attr('room-id');
	 var remove_furniture_id = $(this).attr('furniture-id');
	 var remove_furniture_name = $(this).siblings('.selected-furniture-name').html();
	 $(this).closest('.selected-furniture[furniture-id="' + remove_furniture_id + '"]').detach();
	 $('#room-furnitures[room-id="' + remove_furniture_room_id + '"]').append('<option value="' + remove_furniture_id + '">' + remove_furniture_name + '</option>');
	 $('#single-furniture-container[room-id="' + remove_furniture_room_id + '"]').show();
	 
 }); 
 })( jQuery );