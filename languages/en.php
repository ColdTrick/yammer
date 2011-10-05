<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* English language file
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	$english = array(
		'yammer' => "Yammer Widget",
		
		// Widget
		'yammer:widget:title' => "Yammer Widget",
		'yammer:widget:description' => "Add Yammer support to your Profile or Dashboard",
		
		'yammer:widget:settings:feeds' => "Which Yammer messages would you like to see",
		'yammer:widget:settings:feeds:sent' => "Sent messages",
		'yammer:widget:settings:feeds:received' => "Received messages",
		'yammer:widget:settings:feeds:following' => "Followed",
		'yammer:widget:settings:feeds:all' => "All messages",
		'yammer:widget:settings:count' => "How many messages to show",
	
		'yammer:widget:yammer_profile' => "check my %sYammer%s",
	
		// user settings
		'yammer:usersettings:not_authorized' => "Your account is not yet linked to your Yammer account. Choose 'Link with Yammer' to link your account on this site with your Yammer account.",
		'yammer:usersettings:authorize' => "Link with Yammer",
		'yammer:usersettings:enter_code' => "I have an authorization code",
		'yammer:usersettings:authorized' => "Your account is linked to your Yammer account. If you want to remove the link with ",
		'yammer:usersettings:revoke' => "Remove Yammer link",
		'yammer:usersettings:wire_posts' => "Post your wire messages to Yammer",
		'yammer:usersettings:misconfigured' => "This profile is not yet linked to Yammer.",
		'yammer:usersettings:configure_here' => "You can link with Yammer %shere%s.",
		
		// admin settings
		'yammer:settings:application_key' => "Consumer (Application) Key",
		'yammer:settings:application_secret' => "Consumer (Application) Secret",
		'yammer:settings:misconfigured' => "The Yammer plugin is not yet configured by the admin. Please try again later.",
		
		// message
		'yammer:message:reply_to' => "in reply to",
		'yammer:message:attachment:file' => "File: %s",
		'yammer:message:attachment:link' => "link: %s",
	
		// authorize
		'yammer:authorize:error:access_token' => "Invalid access token, please try again",
		'yammer:authorize:error:tokens' => "Invalid user tokens, please try again",
		'yammer:authorize:success' => "Your Yammer account was successfully authorized",
		
		// actions
		// revoke
		'yammer:action:revoke:success' => "Yammer access succesfully revoked",
		'yammer:action:revoke:error' => "Error while revoking Yammer access",
		
	);

	add_translation("en", $english);

?>