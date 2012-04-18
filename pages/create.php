<?php 

	// title
	$title_text = elgg_echo("videochat:forms:create");
	$content = elgg_view_title($title_text);
	
	$content .= elgg_view("videochat/forms/create");
	
    //select the correct canvas area
	$body = elgg_view_layout("two_column_left_sidebar", "", $content);
		
	// Display page
	page_draw($title_text,$body);
	
?>