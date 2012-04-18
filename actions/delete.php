<?php 

	global $CONFIG;

	admin_gatekeeper();
	
	$result = false;
	
	if($guid = get_input("guid")){
		if($room = get_entity($guid)){
			if($room instanceof ElggObject && $room->getSubtype() == "videochat_room"){
				if($room->delete()){
					system_message(elgg_echo("videochat:actions:delete:succes"));
					$result = true;
				}			
			}
		}
	}
	
	if(!$result){
		register_error(elgg_echo("videochat:actions:delete:error"));
	}

	forward(REFERER);	
?>