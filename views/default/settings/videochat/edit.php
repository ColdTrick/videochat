<?php 

	$settings = $vars["entity"];

	if($settings->enable_popout != "no"){
		$popout_options = "<option value='yes' selected='selected'>" . elgg_echo("option:yes") . "</option>\n";
		$popout_options .= "<option value='no'>" . elgg_echo("option:no") . "</option>\n";
	} else {
		$popout_options = "<option value='yes'>" . elgg_echo("option:yes") . "</option>\n";
		$popout_options .= "<option value='no' selected='selected'>" . elgg_echo("option:no") . "</option>\n";
	}
	
	if($settings->tokbox_method != "api"){
		$method_options = "<option value='embed' selected='selected'>" . elgg_echo("videochat:settings:tokbox:method:embed") . "</option>\n";
		$method_options .= "<option value='api'>" . elgg_echo("videochat:settings:tokbox:method:api") . "</option>\n";
	} else {
		$method_options = "<option value='embed'>" . elgg_echo("videochat:settings:tokbox:method:embed") . "</option>\n";
		$method_options .= "<option value='api' selected='selected'>" . elgg_echo("videochat:settings:tokbox:method:api") . "</option>\n";
	}
	
	if($settings->tokbox_api_server != "api.tokbox.com"){
		$api_server_options = "<option value='sandbox.tokbox.com' selected='selected'>" . elgg_echo("videochat:settings:tokbox:api_server:sandbox") . "</option>\n";
		$api_server_options .= "<option value='api.tokbox.com'>" . elgg_echo("videochat:settings:tokbox:api_server:production") . "</option>\n";
	} else {
		$api_server_options = "<option value='sandbox.tokbox.com'>" . elgg_echo("videochat:settings:tokbox:api_server:sandbox") . "</option>\n";
		$api_server_options .= "<option value='api.tokbox.com' selected='selected'>" . elgg_echo("videochat:settings:tokbox:api_server:production") . "</option>\n";
	}

?>
<div>
	<div><label><?php echo elgg_echo("videochat:settings:enable_popout"); ?></label></div>
	<select name="params[enable_popout]" class="input_pulldown">
		<?php echo $popout_options; ?>
	</select>
	
	<h3 class="settings" ><?php echo elgg_echo("videochat:settings:tokbox"); ?></h3>
	<div><?php echo elgg_echo("videochat:settings:tokbox:info"); ?></div>
	
	<div><label><?php echo elgg_echo("videochat:settings:tokbox:method"); ?></label></div>
	<select name="params[tokbox_method]" class="input_pulldown">
		<?php echo $method_options; ?>
	</select>
	
	<h3 class="settings"><?php echo elgg_echo("videochat:settings:tokbox:embed"); ?></h3>
	<div><?php echo elgg_echo("videochat:settings:tokbox:embed:info"); ?></div>
	<div><img src="<?php echo $vars["url"]; ?>mod/videochat/_graphics/tokbox_embed_example.png" /></div>
	
	<div><label><?php echo elgg_echo("videochat:settings:tokbox:embed_url"); ?></label></div>
	<input type="text" name="params[tokbox_embed_url]" value="<?php echo $settings->tokbox_embed_url; ?>" class="input-text" />
	
	<h3 class="settings"><?php echo elgg_echo("videochat:settings:tokbox:api"); ?></h3>
	<div><?php echo elgg_echo("videochat:settings:tokbox:api:info"); ?></div>
	
	<div><label><?php echo elgg_echo("videochat:settings:tokbox:partner_key"); ?></label></div>
	<input type="text" name="params[tokbox_partner_key]" value="<?php echo $settings->tokbox_partner_key; ?>" />
	
	<div><label><?php echo elgg_echo("videochat:settings:tokbox:partner_secret"); ?></label></div>
	<input type="text" name="params[tokbox_partner_secret]" value="<?php echo $settings->tokbox_partner_secret; ?>" class="input-text" />
	
	<div><label><?php echo elgg_echo("videochat:settings:tokbox:api_server"); ?></label></div>
	<select name="params[tokbox_api_server]" class="input_pulldown">
		<?php echo $api_server_options; ?>
	</select>
	
</div>