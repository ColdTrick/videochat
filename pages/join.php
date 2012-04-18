<?php

	global $CONFIG;

	require_once($CONFIG->pluginspath . "videochat/vendors/callwidget-tokbox-php-sdk-v0/TokBoxCall.php");
	require_once($CONFIG->pluginspath . "videochat/vendors/callwidget-tokbox-php-sdk-v0/TokBoxUser.php");
	
	$group_guid = get_input("group_guid");
	$popout = get_input("popout", false);
	$group_layout = false;
	
	if(!empty($group_guid) && ($group = get_entity($group_guid))){
		if($group instanceof ElggGroup){
			// chat initiated from a group
			if($room_id = $group->videochat_room_id){
				// room previously created
				$room = get_entity($room_id);
				$group_layout = true;
			}
			
			if(!$room){
				// no room was previously created, or something else went wrong, create a new persistent group chatroom
				
				try {
		
					$userObj = TokBoxUser::createGuest();
					$callid =  TokBoxCall::createCall($userObj, true); //generates a persistent (hence the true) call id (not persistent calls will be cleared after 4 days
					
					if(!empty($callid)){
					
						$room =  new ElggObject();
						$room->subtype = "videochat_room";
						$room->owner_guid = $group->getGUID();
						$room->title = sprintf(elgg_echo("videochat:room:group"), $group->name);
						$room->access_id = $group->group_acl;		
						
						if($room->save()){
							$room->callid = $callid;
							$room->persistent = true;
							$group->videochat_room_id = $room->getGUID();
							$group_layout = true;
						} else {
							register_error(elgg_echo("videochat:actions:create:error_save"));
							$forward = REFERER;
						}	
					} else {
						register_error(elgg_echo("videochat:actions:create:no_call_id"));
						$forward = REFERER;
					}
					
				} catch(Exception $e) {
					
					$error = $e->getMessage();
					register_error($error);
					
					$forward = REFERER;
				}
			}
		}
	}
	
	if(empty($room)){
		// no group so try normal join
		$room = get_entity(get_input("guid"));
		
		if($room){
			// check if this is a group room
			$videochat_options = array(
				"type" => "group",
				"limit" => 1,
				"metadata_names" => "videochat_room_id",
				"metadata_values" => $room->getGUID()
			);
 
			if($groups = elgg_get_entities_from_metadata($videochat_options)){
				$group = $groups[0];
				$group_layout = true;
			}
		}
	}
	
	if($group_layout){
		set_context("groups");
		set_page_owner($group->guid);
	}
	
	if($room instanceof ElggObject && $room->getSubtype() == "videochat_room"){
		
		if(!empty($room->callid)){
			$userObj = TokBoxUser::createGuest();
			
			$callUrl = TokBoxCall::generateLink($room->callid);//generate call URL
		} else {
			$error = elgg_echo("videochat:join:no_callid");
		}
	} else {
		$error = elgg_echo("videochat:join:no_room");		
	} 
	
	if(!empty($error)){
		register_error($error);
		forward($CONFIG->wwwroot . "pg/videochat");
	} else {
	    
		// title
		$title_text = elgg_echo("videochat:index:title") . ": " . $room->title;
		$title = elgg_view_title($title_text);
		$body = elgg_view("tokbox/chat", array("callUrl" => $callUrl, "popout" => $popout, "group_layout" => $group_layout, "room" => $room));

		//select the correct canvas area
		if($popout){
			echo "<html><head><title>" . $title_text . "</title></head><body>" . $body . "</body></html>";
		} else {
			if($group_layout){
				
				$page_data = elgg_view_layout("two_column_left_sidebar", "", $title . $body);	
			} else {
				$page_data = elgg_view_layout("one_column", $title . $body);
			}
			
			// Display page
			page_draw($title_text,$page_data);
		}
	}	
?>