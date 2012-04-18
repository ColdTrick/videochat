<?php 

	global $CONFIG;

	require_once($CONFIG->pluginspath . "videochat/vendors/callwidget-tokbox-php-sdk-v0/TokBoxCall.php");
	require_once($CONFIG->pluginspath . "videochat/vendors/callwidget-tokbox-php-sdk-v0/TokBoxUser.php");
	
	$title = get_input("title");
	$access = get_input("access", ACCESS_PRIVATE);
	
	if(!empty($title)){
		try {

			$userObj = TokBoxUser::createGuest();
			$callid =  TokBoxCall::createCall($userObj);//guest access to a call
			//, true); //generates a persistent (hence the true) call id (not persistent calls will be cleared after 4 days
			//$callUrl = TokBoxCall::generateLink($callid);//generate call URL
			
			if(!empty($callid)){
			
				$room =  new ElggObject();
				$room->subtype = "videochat_room";
				$room->title = $title;
				$room->access_id = $access;		
				
				if($room->save()){
					$room->callid = $callid;
					$forward = $CONFIG->wwwroot . "pg/videochat/join/" . $room->getGUID();
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
	} else {
		register_error(elgg_echo("videochat:actions:create:no_title"));
		$forward = REFERER;
	}

	forward($forward);
?>