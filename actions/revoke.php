<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* action to revoke Yammer link
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	gatekeeper();
	
	if(yammer_revoke_access()){
		system_message(elgg_echo("yammer:action:revoke:success"));
	} else {
		register_error(elgg_echo("yammer:action:revoke:error"));
	}

	forward($_SERVER["HTTP_REFERER"]);

?>