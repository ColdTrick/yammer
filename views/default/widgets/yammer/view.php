<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* display Yammer stream in widget
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	$user = page_owner_entity();

	if(yammer_get_class($user->getGUID())){
		
		if(yammer_is_authorized($user->getGUID())){
			
			$message_count = $vars["entity"]->message_count;
			if(empty($message_count)){
				$message_count = 10;
			}
			
			switch($vars["entity"]->message_feed){
				case "received":
					$messages = yammer_get_received_messages($user->getGUID());
					break;
				case "following":
					$messages = yammer_get_following_messages($user->getGUID());
					break;
				case "all":
					$messages = yammer_get_all_messages($user->getGUID());
					break;
				default:
					$messages = yammer_get_sent_messages($user->getGUID());
					break;
			}
			
			if(!empty($messages)){
				foreach($messages as $index => $message){
					if($index < $message_count){
						echo elgg_view("yammer/message", array("message" => $message, "user_guid" => $user->getGUID()));
					} else {
						break;
					}
				}
			}
			
			$yammer_user = yammer_get_current_user($user->getGUID());
			
			if(!empty($yammer_user)){
				$link_begin = "<a href='" . $yammer_user->web_url . "' title='" . $yammer_user->full_name . "' target='_blank'>";
				$link_end = "</a>";
				
				echo "<div class='contentWrapper yammer_widget_profile'>";
				echo sprintf(elgg_echo("yammer:widget:yammer_profile"), $link_begin, $link_end);
				echo "</div>";
			}
		} else {
			echo "<div class='contentWrapper'>";
			echo elgg_echo("yammer:usersettings:misconfigured");
			if(page_owner() == get_loggedin_userid()){
				echo "<br />";
				echo "<br />";
				echo sprintf(elgg_echo("yammer:usersettings:configure_here"), "<a href='" . $vars["url"] . "pg/settings/plugins/" . get_loggedin_user()->username . "'>", "</a>");
			}
			echo "</div>";
		}
	} else {
		echo "<div class='contentWrapper'>";
		echo elgg_echo("yammer:settings:misconfigured");
		echo "</div>";
	}

?>