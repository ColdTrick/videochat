<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 */

require_once 'TokBoxApi.php';
require_once('TokBoxExceptions.php');

class TokBoxContact {

	public static function addFriends(TokBoxApi $userObj, $contacts) {
		$apiObj = new TokBoxApi(API_Config::PARTNER_KEY, API_Config::PARTNER_SECRET);

		$valid = $apiObj->validateAccessToken($userObj->getSecret(), $userObj->getJabberId());

		if(!$valid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$validXml = simplexml_load_string($valid, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($validXml->validateAccessToken->isValid == 'false')
			throw new Exception("The Jabber ID and Access Secret combination you passed in are not valid");

		$addContact = $userObj->addContact($contacts, $userObjObj->getJabberId());

		if(!$addContact)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$addContactXml = simplexml_load_string($addContact, 'SimpleXMLElement', LIBXML_NOCDATA);

		$addContactResults = array();

		foreach($addContactXml->addContact->batchAddResult->contactResult as $result) {
			$addContactResults[$result['jabberId']] = $result['result'];
		}

		return $addContactResults;
	}

	public static function isFriend() {

	}

	public static function removeFriend() {

	}
}
