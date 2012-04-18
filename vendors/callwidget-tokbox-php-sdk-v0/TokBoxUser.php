<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 */

require_once('TokBoxApi.php');
require_once('API_Config.php');

class TokBoxUser {
	public static function createGuest() {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$createGuest = $apiObj->createGuestUser(API_Config::PARTNER_KEY);

		if(!$createGuest) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$createGuestXml = simplexml_load_string($createGuest, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($createGuestXml->error) {
			throw new Exception($createGuestXml->error, (int)$createGuestXml->error['code']);
		}

		$apiObj->setJabberId($createGuestXml->createGuest->jabberId);
		$apiObj->setSecret($createGuestXml->createGuest->secret);

		return $apiObj;
	}

	public static function createUser($jabberId, $accessSecret) {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$valid = $apiObj->validateAccessToken($accessSecret, $jabberId);

		if(!$valid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$validXml = simplexml_load_string($valid, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($validXml->validateAccessToken->isValid == 'false')
			throw new Exception("The Jabber ID and Access Secret combination you passed in are not valid");

		$apiObj->setJabberId($jabberId);
		$apiObj->setSecret($accessSecret);

		return $apiObj;
	}

	public static function loginUser() {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$apiObj->updateToken($apiObj->getRequestToken(API_Config::CALLBACK_URL));
		$apiObj->loginUser();
	}

	public static function registerUser($email, $lastname, $firstname) {
		$apiObj = new TokBoxAPI(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$register = $apiObj->registerUser($email, $lastname, $firstname);

		if(!$register) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$registerXml = simplexml_load_string($register, 'SimpleXMLElement', LIBXML_NOCDATA);

		if(isset($registerXml->error)) {
			throw new Exception($registerXml->error, (int)$registerXml->error['code']);
		}

		$jabberId = $registerXml->registerUser->jabberId;
		$accessSecret = $registerXml->registerUser->secret;

		return self::createUser($jabberId, $accessSecret);
	}

	/**
	 * usage:
  	 *			$apiObj = TokBoxUser::createUser($jabberId, $accessSecret);
  	 *			$profile_array = get_tokbox_user_profile($apiObj,$requested_id);
	 *
	 * @author sumotoy@*****.com
	 */
	public static function getUserProfile(TokBoxApi $userObj, $target_jabberId) {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);
		$valid = $apiObj->validateAccessToken($userObj->getSecret(), $userObj->getJabberId());

		if(!$valid) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$validXml = simplexml_load_string($valid, 'SimpleXMLElement', LIBXML_NOCDATA);
		if($validXml->validateAccessToken->isValid == 'false') {
			throw new Exception("The Jabber ID and Access Secret combination you passed in are not valid");
		}

		$profile = $userObj->getUserProfile($target_jabberId);
		if(!$profile) {
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");
		}

		$profileXml = simplexml_load_string($profile, 'SimpleXMLElement', LIBXML_NOCDATA);
		if(isset($profileXml->error)) {
			throw new Exception($profileXml->error, (int)$profileXml->error['code']);
		}

		$profileResults = array();
		$profileResults['userid'] = (string) $profileXml->user->userid;
		$profileResults['jabberid'] = (string) $profileXml->user->jabberid;
		$profileResults['firstname'] = (string) $profileXml->user->firstname;
		$profileResults['lastname'] = (string) $profileXml->user->lastname;
		$profileResults['displayName'] = (string) $profileXml->user->displayName;
		$profileResults['username'] = (string) $profileXml->user->username;
		$profileResults['isOnline'] = (string) $profileXml->user->isOnline;
		$profileResults['show'] = (string) $profileXml->user->show;

		return $profileResults;
	}

	function get_tokbox_user_profile($apiObj,$jabberId) {
		$profile = TokBoxUser::getUserProfile($apiObj,$jabberId);
		if (is_array($profile)) {
        	return $profile;
		}

		return null;
	}
}
