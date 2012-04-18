<?php 
	global $CONFIG;
		
	function videochat_init(){
		
		// extend the CSS with plugin CSS
		elgg_extend_view("css", "videochat/css");
		
		// register pagehandler for nice URL's
		register_page_handler("videochat", "videochat_page_handler");
		
		// getting plugin settings
		if(get_plugin_setting("tokbox_method", "videochat") == "api"){
			$partner_key = get_plugin_setting("tokbox_partner_key", "videochat");
			$partner_secret = get_plugin_setting("tokbox_partner_secret", "videochat");
			$api_server = get_plugin_setting("tokbox_api_server", "videochat");
			
			if(!empty($partner_key) && !empty($partner_secret) && !empty($api_server)){
				define("TOKBOX_PARTNER_KEY", $partner_key);
				define("TOKBOX_PARTNER_SECRET", $partner_secret);
				define("TOKBOX_API_SERVER", "http://" . $api_server . "/");
			}
		}
	}
	
	function videochat_pagesetup(){
		global $CONFIG;
		
		$context = get_context();
		
		// add to tools menu
		add_menu(elgg_echo('videochat:menu:tools'), $CONFIG->wwwroot . "pg/videochat");
		
		if(get_plugin_setting("tokbox_method") == "api"){
			if($context == "videochat"){
				add_submenu_item(elgg_echo('videochat:menu:rooms'), $CONFIG->wwwroot."pg/videochat");
				if(isloggedin()){
					add_submenu_item(elgg_echo('videochat:menu:create'), $CONFIG->wwwroot."pg/videochat/create");
				}			
			} else {
				if($user = get_loggedin_user()){
					// Get the page owner entity
					$page_owner = page_owner_entity();
			
					// Submenu items for all group pages
					if ($page_owner instanceof ElggGroup && $context == 'groups'){
						if($page_owner->isMember($user)){
							add_submenu_item(elgg_echo("videochat:menu:group"), $CONFIG->wwwroot . "pg/videochat/group/" . $page_owner->getGUID());
						}
					}
				}		
			}
		}	
	}
	
	function videochat_cron(){
		global $CONFIG;
		
		$persistent_id = get_metastring_id('persistent');
		$true_id = get_metastring_id(true);
	
		$wheres = array();
		$wheres[] = "NOT EXISTS (
				SELECT 1 FROM {$CONFIG->dbprefix}metadata md
				WHERE md.entity_guid = e.guid
					AND md.name_id = $persistent_id
					AND md.value_id = $true_id)";
			
		// delete rooms older than 24 hours
		$videochat_options = array(
				"type" => "object",
				"subtype" => "videochat_room",
				"limit" => false,
				"wheres"=> $wheres,
				"created_time_upper" => (time() - 60 * 60 *24)
			);
 
		$current_access = elgg_get_ignore_access(); 
		elgg_set_ignore_access(true);
		
		if($rooms = elgg_get_entities($videochat_options)){
			foreach($rooms as $room){
				$room->delete();				
			}
		}
		elgg_set_ignore_access($current_access);	
	}
	
	function videochat_page_handler($page){
		global $CONFIG;
		
		switch($page[0]){
			case "group":
				if(!empty($page[1])){
					set_input("group_guid", $page[1]);
					include(dirname(__FILE__) . "/pages/join.php");
				} else {
					forward($CONFIG->wwwroot . "pg/videochat");
				}
				break;
			case "create":
				include(dirname(__FILE__) . "/pages/create.php");
				break;
			case "join":
				if(!empty($page[1])){
					set_input("guid", $page[1]);
					include(dirname(__FILE__) . "/pages/join.php");
				} else {
					forward($CONFIG->wwwroot . "pg/videochat");
				}
				break;
			default:
				include(dirname(__FILE__) . "/pages/index.php");
				break;
		}
	}

	// register default Elgg events
	register_elgg_event_handler("init", "system", "videochat_init");
	register_elgg_event_handler("pagesetup", "system", "videochat_pagesetup");

	// register actions
	register_action("videochat/create",false,$CONFIG->pluginspath . "videochat/actions/create.php");
	register_action("videochat/delete",false,$CONFIG->pluginspath . "videochat/actions/delete.php", true);
		
	// Register cron hook
	register_plugin_hook('cron', 'daily', 'videochat_cron');
?>