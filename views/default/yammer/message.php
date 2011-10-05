<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* display of one Yammer message
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	global $reply_running;

	$message = $vars["message"];
	$user_guid = $vars["user_guid"];
	
	if(!empty($message)){
		$sender = yammer_get_user($message->sender_id, $user_guid);
		
		$icon = "<a href='" . $sender->web_url . "' title='" . $sender->full_name . "' target='_blank'>";
		$icon .= "<img src='" . $sender->mugshot_url . "' />";
		$icon .= "</a>";
		
		$details .= "<div class='yammer_message_header'>";
		if(!empty($reply_running)){
			$details .= elgg_echo("yammer:message:reply_to") . "&nbsp;";
		}
		$details .= "<a href='" . $sender->web_url . "' title='" . $sender->full_name . "' target='_blank'>" . $sender->full_name . "</a>";
		$details .= "</div>";
		
		$details .= elgg_view("output/longtext", array("value" => $message->body->plain));
		
		if(!empty($message->attachments)){
			foreach($message->attachments as $attachment){
				$details .= "<div class='yammer_message_attachement'>";
				
				$web_url = $attachment->web_url;
				if ((substr_count($web_url, "http://") == 0) && (substr_count($web_url, "https://") == 0)) { 
					$web_url = "http://" . $web_url; 
				}
				
				$link_begin = "<a href='" . $web_url . "' title='" . $attachment->name . "' target='_blank'>";
				$link_end = "</a>";
				
				switch($attachment->type){
					case "file":
						$link = $link_begin;
						$link .= $attachment->name;
						$link .= $link_end;
						
						$details .= sprintf(elgg_echo("yammer:message:attachment:file"), $link);
						break;
					case "image":
						$details .= $link_begin;
						$details .= "<img src='" . $attachment->image->thumbnail_url . "' />";
						$details .= $link_end;
						break;
					case "ymodule":
						$details .= "<span class='yammer_message_attachement_ymodule'>";
						$details .= "<img src='" . $attachment->ymodule->icon_url . "' />";
						
						$details .= $link_begin;
						$details .= $attachment->name;
						$details .= $link_end;
						
						$details .= "</span>";
						break;
				}
				
				$details .= "</div>";
			}
		}
		
		$details .= "<div class='yammer_message_footer'>";
		$details .= friendly_time(strtotime($message->created_at));
		$details .= "</div>";
		
		if(($message->thread_id != $message->id) && empty($reply_running)){
			$reply_running = true;
			
			if($reply = yammer_get_cached_reply_message($message->thread_id, $message->replied_to_id)){
				$details .= "<div class='yammer_message_reply'>";
				$details .= elgg_view("yammer/message", array("message" => $reply, "user_guid" => $user_guid));
				$details .= "</div>";
			} else {
				$threads = yammer_get_messages_in_thread($message->thread_id, $message->id, $user_guid);
				
				if(!empty($threads)){
					foreach($threads as $thread){
						if($thread->id == $message->replied_to_id){
							yammer_cache_reply_message($message->thread_id, $thread);
							
							$details .= "<div class='yammer_message_reply'>";
							$details .= elgg_view("yammer/message", array("message" => $thread, "user_guid" => $user_guid));
							$details .= "</div>";
						}
					}
				}
			}
			
			$reply_running = false;
		}
		
		echo elgg_view_listing($icon, $details);
		
	}

?>