<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 */

require_once('TokBoxApi.php');

class TokBoxCall {

	public function createCall(TokBoxApi $callApiObj, $persistent='false', $displayname='Guest') {
		$createCall = $callApiObj->createCall($displayname, $callApiObj->getJabberId(), "", $persistent);

		if(!$createCall)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$createCallXml = simplexml_load_string($createCall, 'SimpleXMLElement', LIBXML_NOCDATA);

		return $createCallXml->createCall->callId;
	}

	public static function generateInvite(TokBoxApi $callApiObj, $callId, $calleeJid) {
		$isValid = $callApiObj->validateAccessToken($callApiObj->getSecret(), $callApiObj->getJabberId());

		if(!$isValid)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$isValidXml = simplexml_load_string($isValid, 'SimpleXMLElement', LIBXML_NOCDATA);

		if($isValidXml->validateAccessToken->isValid == 'false') {
			throw new NotLoggedInException("The user is not properly validated");
		}

		$createInvite = $callApiObj->createInvite($callId, $calleeJid, $callApiObj->getJabberId());

		if(!$createInvite)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$createInviteXml = simplexml_load_string($createInvite, 'SimpleXMLElement', LIBXML_NOCDATA);

		return $createInviteXml->createInvite->inviteId;
	}

	public static function joinCall(TokBoxApi $callApiObj, $inviteId) {
		$joinCall = $callApiObj->joinCall($inviteId);

		if(!$joinCall)
			throw new Exception("Unable to connect to ".API_Config::API_SERVER.". Please check to make sure API calls are executing properly");

		$joinCallXml = simplexml_load_string($joinCall, 'SimpleXMLElement', LIBXML_NOCDATA);

		return $joinCallXml->joinCall->callId;
	}

	public static function generateLink($callId) {
		return API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId;
	}

	public static function generateInviteButton() {
		$htmlCode = "<script language=\"javascript\" src=\"SDK/js/TokBoxScript.js\"></script>\n".
					"<div style=\"width:100px;height:30px;background-color:gold;color:blue;line-height:30px;text-align:center;margin:10px;\" onclick=\"inviteUser()\">\n".
					"Invite User\n".
					"</div>\n";

		return $htmlCode;
	}

	public static function generateEmbedCode($callId, $width="425", $height="320", $swfobjectPath="SDK/js/swfobject.js", $inviteButton="true", $guestList="true", $observerMode="false", $textChat="false") {
		$bodyCode = "<script type=\"text/javascript\" src=\"$swfobjectPath\"></script>\n".
					"<script type=\"text/javascript\">\n".
						"\tvar flashvars = {};\n".
						"\tflashvars.inviteButton = \"$inviteButton\";\n".
						"\tflashvars.guestList = \"$guestList\";\n".
						"\tflashvars.observerMode = \"$observerMode\";\n".
						"\tflashvars.textChat = \"$textChat\";\n".
						"\tvar params = {};\n".
						"\tparams.allowfullscreen = \"true\";\n".
						"\tparams.allowscriptaccess = \"always\";\n".
						"\tvar attributes = {};\n".
						"\tattributes.id = \"tbx_call\";\n".
						"\tswfobject.embedSWF(\"".API_Config::API_SERVER.TokBoxApi::$API_SERVER_CALL_WIDGET.$callId."/".API_Config::PARTNER_KEY."\", \"widgetDiv\", \"$width\", \"$height\", \"9.0.115\", false, flashvars, params, attributes);\n".
					"</script>\n".
					"<div id=\"widgetDiv\">\n".
						"\t<a href=\"http://www.adobe.com/go/getflashplayer\">\n".
							"\t\t<img src=\"http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif\" alt=\"Get Adobe Flash player\" />\n".
						"\t</a>\n".
					"</div>\n";

		return $bodyCode;
	}
}
