<?php

 /*
  * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
  *		*************************************
  *		*       TokBox Base API             *
  *		*************************************
  *
  *		Melih Onvural, August 2008
  *
  *
  */

require_once('API_Config.php');

class BaseApi {

	//comes from Tokbox
	protected $partnerKey;
	protected $secret;
	protected $requestToken;

	//comes from the user
	protected $jabberId;

	//comes from the API
	protected $version = "1.0.0";
	protected $sigMethod = "SIMPLE-MD5";

	// API URI for logging a user into their TokBox account
	public static $API_SERVER_LOGIN_URL = "view/oauth&";

	// API URI for making API Calls
	public static $API_SERVER_METHODS_URL = "a/v0";

	// API URI for accessing the call widget
	public static $API_SERVER_CALL_WIDGET = "vc/";

	// API URI for accessing the video recorder widget
	public static $API_SERVER_RECORDER_WIDGET = "vr/";

	// API URI for accessing the video player widget
	public static $API_SERVER_PLAYER_WIDGET = "vp/";


	public function __construct($partnerKey, $partnerSecret) {
		$this->partnerKey = $partnerKey;
		$this->secret = $partnerSecret;
		$this->requestToken = null;
	}

	public function updateToken($newTokenXML) {
		$message = simplexml_load_string($newTokenXML);

		if(!$message)
			throw new Exception("Request was not properly returned. Please make sure that you are connecting to the TokBox API.", 500);

		if(isset($message->error))
			throw new Exception($message->error, (int)$message->error['code']);

		if(isset($message->requestToken))
			$this->requestToken = $message->requestToken->token;
	}

	public function loginUser() {
		if($this->requestToken == null) {
			throw new Exception("You must first retrieve a Request Token before you can make this call", 401);
		}

		$url = API_Config::API_SERVER.self::$API_SERVER_LOGIN_URL."oauth_token=".$this->requestToken;

		header("Location: $url");
	}

	/**Getter Functions**/
	public function getAuthToken() { return $this->requestToken; }
	public function getJabberId() { return $this->jabberId; }
	public function getPartnerKey() { return $this->partnerKey; }
	public function getSecret() { return $this->secret; }

	/**Setter Functions**/
	public function setJabberId($jabberId) {
		$this->jabberId = $jabberId;
	}

	public function setSecret($secret) { $this->secret = $secret;}

	protected function TB_Request($method, $apiURL, $paramList) {
		//build request string
		$reqString = API_CONFIG::API_SERVER.self::$API_SERVER_METHODS_URL.$apiURL;

		//build context by gluing authString to call string
		$nonce = $this->generateNonce();
		$timestamp = time();

		$signedSig = $this->buildSignedRequest($method, $reqString, $nonce, $timestamp, $paramList);
		$authFields = array(	'oauth_partner_key' 		=> $this->partnerKey,
						   		'oauth_signature_method'	=> $this->sigMethod,
						   		'oauth_timestamp'			=> $timestamp,
						   		'oauth_version'				=> $this->version,
						   		'oauth_nonce'				=> $nonce,
						   		'oauth_signature'			=> $signedSig,
						   		'tokbox_jabberid'			=> $this->jabberId
							);

		//add the parameterList items
		$dataString = '';
		foreach($paramList as $k => $v) {
			$v = urlencode($v);
			$k = urlencode($k);
			$dataString .= "$k=$v&";
		}

		$dataString .= "oauth_version=$this->version&";
		$dataString .= "oauth_timestamp=$timestamp&";
		$dataString .= "oauth_nonce=$nonce&";
		$dataString .= "tokbox_jabberid=".rawurlencode($this->jabberId)."&";
		$dataString .= "oauth_partner_key=$this->partnerKey&";
		$dataString .= "oauth_signature_method=$this->sigMethod&";

		//add _AUTHORIZATION
		$dataString .= '_AUTHORIZATION=';

		foreach($authFields as $k => $v) {
			$v = urlencode($v);
			$k = urlencode($k);
			$dataString .= "$k=\"$v\",";
		}

		$dataString = rtrim($dataString,",");

		if (function_exists("file_get_contents")) {
			$context_source = array ('http' => array (
										'method' => 'POST',
										'header'=> "Content-type: application/x-www-form-urlencoded\n"
										. "Content-Length: " . strlen($dataString) . "\n",
										'content' => $dataString
										)
							);

			$context = stream_context_create($context_source);
			$res = @file_get_contents( $reqString ,false, $context);
		}
		else if(function_exists("curl_init")) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $reqString);
			curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Content-type: application/x-www-form-urlencoded'));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

			$res = curl_exec($ch);

			curl_close($ch);
		}
		else {
			throw new Exception("Your PHP installion neither supports the file_get_contents method nor cURL. Please enable one of these functions so that you can make API calls.");
		}

		return $res;
	}

	/*
	 * Use oauth headers/signing methods.
	 *
	 * Request string is built up from METHOD . URI . SORTED_REQUEST_STRING
	 * This is signed with the secret and returned
	*/
	private function buildSignedRequest($method, $uri, $nonce, $timestamp, $paramList) {

		//if not set, version is 1.0
		@$this->version or $this->version = "1.0.0";
		$secret	= $this->secret;

		//no auth/request token yet!
		$signedAuthFields = array( 	'oauth_partner_key' 		=> $this->partnerKey,
						   			'oauth_signature_method'	=> $this->sigMethod,
						   			'oauth_timestamp'			=> $timestamp,
						   			'oauth_version'				=> $this->version,
						   			'oauth_nonce'				=> $nonce,
						   			'tokbox_jabberid'			=> $this->jabberId
								);

		$paramList = array_merge($paramList, $signedAuthFields);

		//Calculate the request signature - doesn't include params with underscores
		//Sort the paramaters w/o underscores
		$requestString = $method . "&" . $uri . "&" . $this->generateRequestString($paramList);

		//Sign the requestString with the stored secret, and check it against the passed sig
		switch($this->sigMethod) {
		case "SIMPLE-MD5":
			$signedString = md5($requestString . $secret);
			break;
		case "HMAC-SHA1":
			$signedString = base64_encode( hash_hmac('sha1', $requestString, $secret, true));
			break;
		default:
			throw new Exception("No signature method selected");
		}

		return $signedString;
	}

	private function generateRequestString($paramList) {
		if(!$paramList || !is_array($paramList)) {
	   		throw new Exception("Empty parameter list");
		}

		$encodedParamList = array();

		foreach($paramList as $key => $value) {
			//Discard parameters starting with _
			if($key[0] == '_') {
				continue;
			}

			if((string)$value != null && (string)$value != "") {
					$encodedKey = rawurlencode($key);
					$encodedValue = rawurlencode($value);
					$encodedParamList[] = $encodedKey . "=" . $encodedValue;
			}
		}

		sort($encodedParamList, SORT_STRING);

		return join($encodedParamList,"&");
	}

	private function generateNonce() {
		$rand_str = "";
  		$size = 16;

		$feed = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

		$feed_length = strlen($feed)-1;

	  	for ($i=0; $i < $size; $i++){
      		$rand_str .= substr($feed, mt_rand(0, $feed_length), 1);
  		}

	  	return $rand_str;
	}
}
