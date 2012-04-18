<?php 

	$room = $vars["entity"];

?>
<div class="videochat_listing contentWrapper">
<div class="videochat_listing_actions">
	<a class="submit_button" href="<?php echo $vars["url"]; ?>pg/videochat/join/<?php echo $room->getGUID(); ?>"><?php echo elgg_echo("videochat:room:join"); ?></a>
	<?php 
		if(isadminloggedin()){ 
			echo elgg_view("output/confirmlink", array("href" => $vars["url"] . "action/videochat/delete?guid=" . $room->getGUID(), "text" => elgg_echo("delete"), "class" => "submit_button"));
		}
	?>
</div>
<?php 
	echo "<strong>" . $room->title . "</strong>";
	echo " " . elgg_echo("videochat:room:created_by") . " <a href='" . $room->getOwnerEntity()->getUrl() . "'>" . $room->getOwnerEntity()->name . "</a> ";
	echo elgg_view_friendly_time($room->time_created);
?>
</div>