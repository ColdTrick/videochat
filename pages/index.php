<?php 

	global $CONFIG;

	// title
	$title_text = elgg_echo("videochat:index:title");
	$content = elgg_view_title($title_text);
	
	
	if(get_plugin_setting("tokbox_method", "videochat") != "embed"){
	
		$rooms_options = array(
				"type" => "object",
				"subtype" => "videochat_room",
				"limit" => false
			);
			
		$rooms = elgg_list_entities($rooms_options);
		if(empty($rooms)){
			$body = elgg_echo("videochat:rooms:no_rooms");
			if(isloggedin()){
				$body .= " " . sprintf(elgg_echo("videochat:rooms:start_one"), "<a href='" . $CONFIG->wwwroot ."pg/videochat/create'>", "</a>");
			} else {
				$body .= " " . elgg_echo("videochat:rooms:login_to_start_one");
			}
			
			$rooms = elgg_view("page_elements/contentwrapper", array("body" => $body));
		}
		
		$content .= $rooms;
		
	    //select the correct canvas area
		$body = elgg_view_layout("two_column_left_sidebar", "", $content);

	} else {
		$title = elgg_view_title($title_text);
		$callUrl = get_plugin_setting("tokbox_embed_url", "videochat");
		$content = elgg_view("tokbox/chat", array("callUrl" => $callUrl));
		$body = elgg_view_layout("one_column", $title . $content);
	}
	
	// Display page
	page_draw($title_text,$body);
?>