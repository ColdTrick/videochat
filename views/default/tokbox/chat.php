<?php 

	$flash_vars = "&textChat=true";

	if($user = get_loggedin_user()){
		$flash_vars .= "&displayName=" . $user->name;
	} elseif($name = get_input("name", false)){
		$flash_vars .= "&displayName=" . $name;
	}
	
	$width = 900;
	$height = 550;
	
	if(!$vars["popout"]){
		if($vars["group_layout"]){
			$width = 650;
		}
	
		if($room = $vars["room"]){
			$popout_url = $vars["url"] . "pg/videochat/join/" . $room->getGUID() . "?popout=true";
		}
		
		$popout = elgg_view("videochat/popout", array("room_container" => "videochat_tokbox_room", "popout_url" => $popout_url));
	} else {
		$width = "100%";
		$height = "100%";
	}
?>

<div class='contentWrapper'>
	<?php 
		echo $popout;
		
		if(!empty($vars["callUrl"])){ ?>
	<div id="videochat_tokbox_room">
		<center>
		<object type="application/x-shockwave-flash" data="<?php echo $vars["callUrl"];?>" width="<?php echo $width;?>" height="<?php echo $height;?>">
			<param name="movie" value="<?php echo $vars["callUrl"];?>" />
			<param name="allowFullScreen" value="true" />
			<param name="allowScriptAccess" value="always" />
			<param name="flashvars" value="<?php echo $flash_vars; ?>" />
			<param name="wmode" value="transparent" />
		</object>
		</center>
	</div>
	<?php 
		} else {
			echo elgg_echo("videochat:rooms:missing_call_url");
		}
	?>
</div>