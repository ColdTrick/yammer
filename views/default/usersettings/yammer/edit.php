<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* User settings
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	if(yammer_get_class()){
		$user = get_loggedin_user();
		
		echo "<img id='yammer_usersettings_logo' src='" . $vars["url"]. "mod/yammer/_graphics/YammerIconApp40x40.png'>";
		echo "<div id='yammer_usersettings_actions'>";
		if(!yammer_is_authorized()){
			$callback_url = $vars["url"] . "pg/yammer/authorize";
			$request_url = yammer_get_authorize_url($callback_url);
			
			
			echo elgg_echo("yammer:usersettings:not_authorized");
			echo "<div>";
			echo "<a class='submit_' href='" . $request_url . "' target='_blank'>" . elgg_echo("yammer:usersettings:authorize") . "</a>";
			echo " | <a class='submit_' href='javascript:void(0);' onclick='$(\"#yammer_authorize_wrapper\").toggle();'>" . elgg_echo("yammer:usersettings:enter_code") . "</a>";
			echo "</div>";
			$form_data .= "<div id='yammer_authorize_wrapper'>";
			$form_data .= "<input type='text' name='oauth_verifier' size='25'/>";
			$form_data .= "&nbsp;";
			$form_data .= elgg_view("input/button", array("value" => elgg_echo("submit"),
															"js" => "onclick='yammer_authorize();'",
															"type" => "button"));
			$form_data .= "</div>";
			
			echo $form_data;
			
		} else {
			
			$revoke_url = elgg_add_action_tokens_to_url($vars["url"] . "action/yammer/revoke");
			
			echo elgg_echo("yammer:usersettings:authorized");
			echo "<div>";
			echo "<a href='" . $revoke_url . "'>" . elgg_echo("yammer:usersettings:revoke") . "</a>";
			echo "</div>";
			
			
			if(is_plugin_enabled("thewire")){
				echo "<br />";
				
				echo "<div>";
				echo elgg_echo("yammer:usersettings:wire_posts");
				echo " <select name='params[post_wire_messages]'>";
				echo "<option value='yes'";
				if ($vars['entity']->post_wire_messages == 'yes'){
					echo " selected='yes'"; 
				}
				echo ">" . elgg_echo('option:yes'). "</option>";
				echo "<option value='no'";
				if ($vars['entity']->post_wire_messages != 'yes'){
					echo " selected='yes'"; 
				}
				echo ">" . elgg_echo('option:no'). "</option>";
				echo "</select>";
				echo "</div>";
			}
		}
	
		echo "</div>";
		echo "<div class='clearfloat'></div>";
	?>
	<script type="text/javascript">
		function yammer_authorize(){
			var oauth_verifier = $('#yammer_authorize_wrapper input[name="oauth_verifier"]').val();
	
			if(oauth_verifier != ""){
				document.location.href = "<?php echo $callback_url; ?>?oauth_verifier=" + oauth_verifier;
			}
		}
		
		$("#yammer_authorize_wrapper input[name='oauth_verifier']").keypress(function(event) {
			 if((event.keyCode || event.which || event.charCode || 0) == 13){
				event.preventDefault();
			 	yammer_authorize();
			}
		});
				
	
	</script>
	<?php 
	} else {
		echo elgg_echo("yammer:settings:misconfigured");
	}
	
?>