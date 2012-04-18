<?php 

	$container = $vars["room_container"]; // id of element that will be removed after popout
	$popout_url = $vars["popout_url"];
	
	if(get_plugin_setting("enable_popout", "videochat") != "no" && $popout_url){
?>
<div id="videochat_popout">
	<a href="#" onclick="videochat_popout();"><?php echo elgg_echo("videochat:popout")?></a>
	<span><?php echo elgg_echo("videochat:popout:info")?></span>
	<div class="clearfloat"></div>	
</div>
<script type="text/javascript">
	function videochat_popout(){
		<?php if($container){?>
			$("#<?php echo $container;?>").remove();		
		<?php } ?>

		window.open("<?php echo $popout_url;?>");
		
		$("#videochat_popout span").show();
		$("#videochat_popout a").hide();
	}
</script>
<?php } ?>