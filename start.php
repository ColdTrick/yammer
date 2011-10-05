<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	require_once(dirname(__FILE__) . "/lib/classes.php");
	require_once(dirname(__FILE__) . "/lib/functions.php");

	function yammer_init(){
		// register widget
		add_widget_type('yammer', elgg_echo("yammer:widget:title"), elgg_echo("yammer:widget:description"));
		
		// register pahe handler for nice URL's
		register_page_handler("yammer", "yammer_page_handler");
		
		// extend css
		elgg_extend_view("css", "yammer/css");
		
	}
	
	function yammer_page_handler($page){
		
		switch($page[0]){
			case "authorize":
				include(dirname(__FILE__) . "/procedures/authorize.php");
				break;
		}
	}
	
	function yammer_tweet_twitter_service($hook_name, $entity_type, $return_value, $parameters){
		if(isloggedin() && $parameters["plugin"] == "thewire" && !empty($parameters["message"])){
			if(get_plugin_usersetting('post_wire_messages', get_loggedin_userid(), 'yammer') == "yes"){
				yammer_messages_post($parameters["message"]);
			}
		}
	}

	// register default Elgg events
	register_elgg_event_handler("init", "system", "yammer_init");

	// register actions
	register_action("yammer/revoke", false, dirname(__FILE__) . "/actions/revoke.php");
	
	// register plugin hooks
	register_plugin_hook("tweet", "twitter_service", "yammer_tweet_twitter_service");
?>