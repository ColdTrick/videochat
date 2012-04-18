<?php

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
		'videochat:menu:tools' => "Videochat",
		'videochat:menu:rooms' => "All videochatrooms",
		'videochat:menu:create' => "Create a new videochatroom",
		'videochat:menu:group' => "Group videochat",
		'videochat:index:title' => "Videochat",
	
		// settings
		'videochat:settings:enable_popout' => "Enable pop-out",
		
		'videochat:settings:tokbox' => "Tokbox settings",
		'videochat:settings:tokbox:info' => "Please select how you wish to use Tokbox.<br /> When using embed you only have one chatroom for the entire site, but it is free.<br /> If you wish to have multiple or group chatrooms you have to use API keys, these however require a small fee.",
		
		'videochat:settings:tokbox:method' => "Tokbox method",
		'videochat:settings:tokbox:method:embed' => "Use embed URL (one chatroom for the entire site)",
		'videochat:settings:tokbox:method:api' => "Use API keys",
		
		'videochat:settings:tokbox:embed' => "Tokbox embed settings",
		'videochat:settings:tokbox:embed:info' => "To get the Embed URL go to http://me.tokbox.com/#embed=call and click on 'Add to my site' and copy the URL from the param with the name 'movie'",
		'videochat:settings:tokbox:embed_url' => "Embed URL",
		
		'videochat:settings:tokbox:api' => "Tokbox API settings",
		'videochat:settings:tokbox:api:info' => "In order to get API key you have to go to http://sandbox.tokbox.com/view/platformkeys (for Sandbox keys) or http://me.tokbox.com/platformsignup (for Production keys)",
		
		'videochat:settings:tokbox:partner_key' => "Partner key",
		'videochat:settings:tokbox:partner_secret' => "Partner secret",
		'videochat:settings:tokbox:api_server' => "API Server",
		'videochat:settings:tokbox:api_server:sandbox' => "Sandbox",
		'videochat:settings:tokbox:api_server:production' => "Production",
	
		// Rooms
		'videochat:rooms:no_rooms' => "There are currently no active videochat rooms.",
		'videochat:rooms:start_one' => "Start one %shere%s!",
		'videochat:rooms:login_to_start_one' => "Login or register to start one.",
		'videochat:rooms:missing_call_url' => "Could not start a videochatroom. Room url is missing. Please configure the videochat plugin!",
		
		// popout
		'videochat:popout' => "Open chatroom in separate window/tab",
		'videochat:popout:info' => "This chatroom is opened in a separate window/tab",
		
		// object
		'videochat:room:created_by' => "created by",
		'videochat:room:join' => "Join",
		'videochat:room:group' => "Group: %s",
	
		// create
		'videochat:forms:create' => "Create a new videochatroom",
		'videochat:forms:create:title' => "Name of the chatroom",
		'videochat:forms:create:access' => "Who can see this chatroom in the list?",
		
		// join
		'videochat:join:no_room' => "There is no room available with the provided id.",
		'videochat:join:no_callid' => "There is no room available with the provided id.",
	
		// actions
		'videochat:actions:create:no_title' => "Please enter a title for this chatroom",
		'videochat:actions:create:error_save' => "An error occured while saving the chatroom",
	
		'videochat:actions:delete:succes' => "Delete success",
		'videochat:actions:delete:error' => "Delete failed",
	
	);
					
	add_translation("en",$english);

?>