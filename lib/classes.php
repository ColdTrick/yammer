<?php 
	/**
	* Yammer
	* Developed for Océ Technologies
	* 
	* Classes needed for OAuth authentication
	* 
	* @package yammer
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2010
	* @link http://www.coldtrick.com/
	*/

	if(!class_exists("OAuthConsumer") && !is_plugin_enabled("twitterservice")){
		require_once(dirname(dirname(__FILE__)) . "/vendors/oauth/OAuth.php");
	}
	
	class YammerOAuth {
		
		/* Set up the API root URL. */
		private $host = "https://www.yammer.com/api/v1/";
		/* Set timeout default. */
		private $timeout = 30;
		/* Set connect timeout. */
		private $connecttimeout = 30; 
		/* Verify SSL Cert. */
		private $ssl_verifypeer = FALSE;
		/* Respons format. */
		private $format = "json";
		/* Decode returned json data. */
		private $decode_json = TRUE;
		/* Set the useragnet. */
		private $useragent = "YammerOAuth v0.1-beta";
  
		/* Contains the last HTTP headers returned. */
		public $http_info;
		/* Contains the last HTTP status code returned. */
		public $http_code;
		/* Contains the last API call. */
		public $url;
  
		
		function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
			$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
			$this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
			
			if (!empty($oauth_token) && !empty($oauth_token_secret)) {
				$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
			} else {
				$this->token = NULL;
			}
		}
		
		function requestTokenURL(){
			return "https://www.yammer.com/oauth/request_token";
		}
		
		function accessTokenURL(){
			return "https://www.yammer.com/oauth/access_token";
		}
		
		function authorizeURL(){
			return "https://www.yammer.com/oauth/authorize";
		}
		
		function lastStatusCode() { 
			return $this->http_status; 
		}
		
		function lastAPICall() { 
			return $this->last_api_call; 
		}
		
		function oAuthRequest($url, $method, $parameters) {
			if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
				$url = "{$this->host}{$url}.{$this->format}";
			}
			
			$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
			$request->sign_request($this->sha1_method, $this->consumer, $this->token);
			
			switch ($method) {
				case 'GET':
					return $this->http($request->to_url(), 'GET');
					break;
				default:
					return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata());
					break;
			}
		}
		
		function http($url, $method, $postfields = NULL) {
			$this->http_info = array();
			$ci = curl_init();
			
			/* Curl settings */
			curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
			curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
			curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
			curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
			curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
			curl_setopt($ci, CURLOPT_HEADER, FALSE);
			
			switch ($method) {
				case "POST":
					curl_setopt($ci, CURLOPT_POST, TRUE);
					if (!empty($postfields)) {
						curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
					}
					break;
				case "DELETE":
					curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
					if (!empty($postfields)) {
						$url = "{$url}?{$postfields}";
					}
					break;
			}
		
			curl_setopt($ci, CURLOPT_URL, $url);
			
			$response = curl_exec($ci);
			
			$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
			$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
			$this->url = $url;
			
			curl_close ($ci);
			
			return $response;
		}
		
		function getHeader($ch, $header) {
			$i = strpos($header, ':');
			
			if (!empty($i)) {
				$key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
				$value = trim(substr($header, $i + 2));
				$this->http_header[$key] = $value;
			}
			
			return strlen($header);
		}

		function getRequestToken($oauth_callback = NULL) {
			$parameters = array();
			
			if (!empty($oauth_callback)) {
				$parameters['oauth_callback'] = $oauth_callback;
			}
			
			$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
			
			$token = OAuthUtil::parse_parameters($request);
			$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
			
			return $token;
		}
		
		function getAuthorizeURL($token) {
			if (is_array($token)) {
				$token = $token['oauth_token'];
			}
			
			return $this->authorizeURL() . "?oauth_token={$token}";
		}
		
		function getAccessToken($oauth_verifier = FALSE) {
			$parameters = array();
			
			if (!empty($oauth_verifier)) {
				$parameters['oauth_verifier'] = $oauth_verifier;
			}
			
			$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
			
			$token = OAuthUtil::parse_parameters($request);
			$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
			
			return $token;
		}
		
		function get($url, $parameters = array()) {
			$response = $this->oAuthRequest($url, 'GET', $parameters);
			
			if ($this->format === 'json' && $this->decode_json) {
				$response = json_decode($response);
			}
			
			return $response;
		}
		  
		function post($url, $parameters = array()) {
			$response = $this->oAuthRequest($url, 'POST', $parameters);
			
			if ($this->format === 'json' && $this->decode_json) {
				$response = json_decode($response);
			}
			
			return $response;
		}
		
		function delete($url, $parameters = array()) {
			$response = $this->oAuthRequest($url, 'DELETE', $parameters);
			
			if ($this->format === 'json' && $this->decode_json) {
				$response = json_decode($response);
			}
			
			return $response;
		}

		function setToken($token){
			$result = false;
			
			if(!empty($token) && is_array($token)){
				if(array_key_exists("oauth_token", $token) && array_key_exists("oauth_token_secret", $token)){
					$this->token = new OAuthConsumer($token["oauth_token"], $token["oauth_token_secret"]);
				}
			}
			
			return $result;
		}
		
	}

?>