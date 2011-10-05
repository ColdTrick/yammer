<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* widget settings
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	if(yammer_get_class(page_owner())){
		$widget = $vars["entity"];
		
		$feeds = array("sent", "received", "following", "all");
		
		$feed_selector = "<select name='params[message_feed]'>\n";
		foreach($feeds as $feed){
			if($feed == $widget->message_feed){
				$feed_selector .= "<option value='" . $feed . "' selected='selected'>" . elgg_echo("yammer:widget:settings:feeds:" . $feed) . "</option>\n";
			} else {
				$feed_selector .= "<option value='" . $feed . "'>" . elgg_echo("yammer:widget:settings:feeds:" . $feed) . "</option>\n";
			}
		}
		$feed_selector .= "</select>\n";
	
		$count_selector = "<select name='params[message_count]'>\n";
		for($i = 1; $i <= 20; $i++){
			if($i == $widget->message_count){
				$count_selector .= "<option value='" . $i . "' selected='selected'>" . $i . "</option>\n";
			} elseif(empty($widget->message_count) && $i == 10){
				$count_selector .= "<option value='" . $i . "' selected='selected'>" . $i . "</option>\n";
			} else {
				$count_selector .= "<option value='" . $i . "'>" . $i . "</option>\n";
			}
			
		}
		$count_selector .= "</select>\n";
		
	?>
	
	<div><?php echo elgg_echo("yammer:widget:settings:feeds"); ?></div>
	<?php echo $feed_selector; ?> 
	
	<div><?php echo elgg_echo("yammer:widget:settings:count"); ?></div>
	<?php echo $count_selector; ?> 
	
<?php } ?>