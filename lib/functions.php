<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* general plugin functions
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	function yammer_get_class($user_guid = 0){
		global $SESSION;
		
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		$application_key = get_plugin_setting("application_key", "yammer");
		$application_secret = get_plugin_setting("application_secret", "yammer");
		
		if(!empty($application_key) && !empty($application_secret)){
			if(!empty($user_guid)){
				$token = get_plugin_usersetting("oauth_token", $user_guid, "yammer");
				$token_secret = get_plugin_usersetting("oauth_token_secret", $user_guid, "yammer");
			}

			if(empty($token) || empty($token_secret)){
				$token = null;
				$token_secret = null;
			}
				
			$result = new YammerOAuth($application_key, $application_secret, $token, $token_secret);
		}
		
		return $result;
	}
	
	function yammer_get_authorize_url($callback = null){
		global $SESSION;
		
		$result = false;
		
		if($yammer = yammer_get_class()){
			$token = $yammer->getRequestToken($callback);
			
			$SESSION["yammer"] = array(
				"oauth_token" => $token["oauth_token"],
				"oauth_token_secret" => $token["oauth_token_secret"]
			);
			
			$result = $yammer->getAuthorizeURL($token);
		}
		
		return $result;
	}
	
	function yammer_get_access_token($oauth_verifier = null){
		global $SESSION;
		
		$result = false;
		
		if($yammer = yammer_get_class(0, $token, $token_secret)){
			if(!empty($SESSION["yammer"]["oauth_token"]) && !empty($SESSION["yammer"]["oauth_token_secret"])){
				$yammer->setToken($SESSION["yammer"]);
			}
			
			$result = $yammer->getAccessToken($oauth_verifier);
		}
		
		return $result;
	}

	function yammer_revoke_access($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			set_plugin_usersetting("oauth_token", null, $user_guid, "yammer");
			set_plugin_usersetting("oauth_token_secret", null, $user_guid, "yammer");
			
			$result = true;
		}
		
		return $result;
	}
	
	function yammer_is_authorized($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			$token = get_plugin_usersetting("oauth_token", $user_guid, "yammer");
			$token_secret = get_plugin_usersetting("oauth_token_secret", $user_guid, "yammer");
			
			if(!empty($token) && !empty($token_secret)){
				$result = true;
			}
		}
		
		return $result;
	}

	function yammer_get_all_messages($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))){
				$response = $yammer->get("messages");
				
				if(!empty($response)){
					$result = $response->messages;
				}
			}
		}
		
		return $result;
	}
	
	function yammer_get_sent_messages($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))){
				$response = $yammer->get("messages/sent");
				
				if(!empty($response)){
					$result = $response->messages;
				}
			}
		}
		
		return $result;
	}
	
	function yammer_get_received_messages($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))){
				$response = $yammer->get("messages/received");
				
				if(!empty($response)){
					$result = $response->messages;
				}
			}
		}
		
		return $result;
	}
	
	function yammer_get_following_messages($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid)){
			if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))){
				$response = $yammer->get("messages/following");
				
				if(!empty($response)){
					$result = $response->messages;
				}
			}
		}
		
		return $result;
	}
	
	function yammer_get_messages_in_thread($thread_id, $older_than = 0, $user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!empty($user_guid) && !empty($thread_id)){
			if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))){
				$params = array();
				
				if(!empty($older_than)){
					$params["older_than"] = $older_than;
				}
				
				$response = $yammer->get("messages/in_thread/" . $thread_id, $params);
				
				if(!empty($response)){
					$result = $response->messages;
				}
			}
		}
		
		return $result;
	}
	
	function yammer_get_user($yammer_user_id, $user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!isset($_SESSION["yammer_cache"])){
			$_SESSION["yammer_cache"] = array();
		}
		
		if(!isset($_SESSION["yammer_cache"]["users"])){
			$_SESSION["yammer_cache"]["users"] = array();
		}
		
		if(!empty($_SESSION["yammer_cache"]["users"][$yammer_user_id])){
			$result = $_SESSION["yammer_cache"]["users"][$yammer_user_id];
		} elseif(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))) {
			$response = $yammer->get("users/" . $yammer_user_id);
			
			if(!empty($response)){
				$_SESSION["yammer_cache"]["users"][$yammer_user_id] = $response;
				$result = $response;
			}
		}
		
		return $result;
	}
	
	function yammer_get_current_user($user_guid = 0){
		$result = false;
		
		if(empty($user_guid)){
			$user_guid = get_loggedin_userid();
		}
		
		if(!isset($_SESSION["yammer_cache"])){
			$_SESSION["yammer_cache"] = array();
		}
		
		if(!isset($_SESSION["yammer_cache"]["users"])){
			$_SESSION["yammer_cache"]["users"] = array();
		}
		
		if(yammer_is_authorized($user_guid) && ($yammer = yammer_get_class($user_guid))) {
			$response = $yammer->get("users/current");
			
			if(!empty($response)){
				$_SESSION["yammer_cache"]["users"][$response->id] = $response;
				$result = $response;
			}
		}
		
		return $result;
	}

	function yammer_messages_post($message){
		
		if(isloggedin() && !empty($message)){
			if(yammer_is_authorized() && ($yammer = yammer_get_class())){
				$parameters = array("body" => $message);
				$response = $yammer->post("messages", $parameters);
				
			}
		}
	}
	
	function yammer_cache_reply_message($thread_id, $message){
		$result = false;
		
		if(!empty($message) && !empty($thread_id)){
			
			if(!isset($_SESSION["yammer_cache"])){
				$_SESSION["yammer_cache"] = array();
			}
			
			if(!isset($_SESSION["yammer_cache"]["messages"])){
				$_SESSION["yammer_cache"]["messages"] = array();
			}
			
			if(!isset($_SESSION["yammer_cache"]["messages"][$thread_id])){
				$_SESSION["yammer_cache"]["messages"][$thread_id] = array();
			}
			
			$_SESSION["yammer_cache"]["messages"][$thread_id][$message->id] = $message;
			$result = true;
		}
		
		return $result;
	}
	
	function yammer_get_cached_reply_message($thread_id, $message_id){
		$result = false;
		
		if(!empty($thread_id) && !empty($message_id)){
			if(isset($_SESSION["yammer_cache"]["messages"][$thread_id][$message_id])){
				$result = $_SESSION["yammer_cache"]["messages"][$thread_id][$message_id];
			}
		}
		
		return $result;
	}
?>