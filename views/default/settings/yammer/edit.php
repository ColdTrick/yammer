<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* general plugin settings
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	$plugin = $vars["entity"];

?>
<div>
	<div><?php echo elgg_echo("yammer:settings:application_key"); ?></div>
	<?php echo elgg_view("input/text", array("internalname" => "params[application_key]", "value" => $plugin->application_key)); ?>
	
	<div><?php echo elgg_echo("yammer:settings:application_secret"); ?></div>
	<?php echo elgg_view("input/text", array("internalname" => "params[application_secret]", "value" => $plugin->application_secret)); ?>
	
</div>