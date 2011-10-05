<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* process Yammer OAuth authorization 
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	global $CONFIG;
	
	gatekeeper();
	
	$oauth_verifier = get_input("oauth_verifier", NULL);
	
	$token = yammer_get_access_token($oauth_verifier);
	
	if(!empty($token) && is_array($token)){
		if(array_key_exists("oauth_token", $token) && array_key_exists("oauth_token_secret", $token)){
			$user_token = $token["oauth_token"];
			$user_token_secret = $token["oauth_token_secret"];
			
			if(!empty($user_token) && !empty($user_token_secret)){
				// only one user per tokens
				$values = array(
					'plugin:settings:yammer:oauth_token' => $user_token,
					'plugin:settings:yammer:oauth_token_secret' => $user_token_secret,
				);
			
				if ($users = get_entities_from_private_setting_multi($values, "user", "", 0, "", 0)) {
					foreach ($users as $user) {
						// revoke access
						yammer_revoke_access($user->getGUID());
					}
				}
				
				set_plugin_usersetting("oauth_token", $user_token);
				set_plugin_usersetting("oauth_token_secret", $user_token_secret);
				
				system_message(elgg_echo("yammer:authorize:success"));
			} else {
				register_error(elgg_echo("yammer:authorize:error:tokens"));
			}
		} else {
			register_error(elgg_echo("yammer:authorize:error:access_token"));
		}
	} else {
		register_error(elgg_echo("yammer:authorize:error:access_token"));
	}

	forward($CONFIG->wwwroot . "pg/settings/plugins")

?>