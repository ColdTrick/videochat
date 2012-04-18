<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 *
 *		*************************************
 *		*            TokBox API             *
 *		*************************************
 *
 *		Jason Friedman, Melih Onvural, August 2008
 *
 *	This class implements all of the methods exposed by the TokBox API.
 *
 */

require_once('BaseApi.php');

final class TokBoxApi extends BaseApi {

	function __construct($userKey, $userSecret) {
		parent::__construct($userKey, $userSecret);
	}

	/**
	 *Creates a request token tied to the creating partner.
	 *This can be exchanged on the TokBox site for an access token, which is then passed to API calls as authentication on user actions.
	 *TODO:
	 *Pass the request token to ADD URL.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $callbackUrl URL to send the user to after they log in.
	 *
	 *@return Response string to API call
	*/
	public function getRequestToken($callbackUrl) {
		$method = "POST";
		$url = "/auth/getRequestToken";
		$paramList = array();
		$paramList['callbackUrl'] = $callbackUrl;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Retrieves an access token appropriate to the credentials of the user who is requesting the token.
	 *If the user is valid, and registered, they will receive an access token associated with their account. Otherwise they will receive a guest token.
	 *
	 *AuthLevel: require_trusted_partner
	 *
	 *@param string $jabberId Jabber ID for the user who is attempting to retrieve the token.
	 *@param string $password MD5 hashed password for the user who is attempting to retrieve the token.
	 *
	 *@return Response string to API call
	*/
	public function getAccessToken($password, $jabberId) {
		$method = "POST";
		$url = "/auth/getAccessToken";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['password'] = $password;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Ensures that the access token and the associated user are correlated by the TokBox system.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $jabberId Jabber ID of the user who is being validated
	 *@param string $accessSecret Access token which is being validated
	 *
	 *@return Response string to API call
	*/
	public function validateAccessToken($accessSecret, $jabberId) {
		$method = "POST";
		$url = "/auth/validateAccessToken";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['accessSecret'] = $accessSecret;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create a call and return the media server address and call id of the video chat.
	 *The Jabber ID and name are used for logging.
	 *After creating a call, create and send invites to any party you wish to join you.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $callerJabberId Jabber ID of the caller creating the video chat.
	 *@param string $callerName Name of the caller creating the video chat.
	 *@param string $features Advanced features.
	 *@param boolean $persistent True if this callid should remain valid past the normal 4 day timeout
	 *@param integer $callInstanceId call_instance_id of the moderated call. If not passed, call is unmoderated
	 *
	 *@return Response string to API call
	*/
	public function createCall($callerName, $callerJabberId, $features = null, $persistent = null, $callInstanceId = null) {
		$method = "POST";
		$url = "/calls/create";
		$paramList = array();
		$paramList['callerJabberId'] = $callerJabberId;
		$paramList['callerName'] = $callerName;
		if($features !== null) $paramList['features'] = $features;
		if($persistent !== null) $paramList['persistent'] = $persistent;
		if($callInstanceId !== null) $paramList['callInstanceId'] = $callInstanceId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create an invite to a particular call. Returns an inviteId to be sent to call recipients
	 *The calleeJid is used for logging and missed call notifications
	 *Clients are expected to use inviteIds to join calls
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $callerJabberId Jabber ID of the inviter who has initiated the call.
	 *@param jid $calleeJabberId Jabber ID of the invitee who is being invited to the call
	 *@param string $callId CallId returned from /calls/create API call
	 *
	 *@return Response string to API call
	*/
	public function createInvite($callId, $calleeJabberId, $callerJabberId) {
		$method = "POST";
		$url = "/calls/invite";
		$paramList = array();
		$paramList['callerJabberId'] = $callerJabberId;
		$paramList['calleeJabberId'] = $calleeJabberId;
		$paramList['callId'] = $callId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns whether or not a call id still exists
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $callId Call ID returned from /call/create.
	 *
	 *@return Response string to API call
	*/
	public function validateCallID($callId) {
		$method = "POST";
		$url = "/calls/validate";
		$paramList = array();
		$paramList['callId'] = $callId;
		return $this->TB_Request($method, $url, $paramList);
	}

	/**
	 *Resets a call's server in memcache in an attempt to allow call owners to restart their calls
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $callId Call ID returned from /call/create.
	 *
	 *@return Response string to API call
	*/
	public function resetCall($callId) {
		$method = "POST";
		$url = "/calls/reset";
		$paramList = array();
		$paramList['callId'] = $callId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Send a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $vmailId VmailId of the recorded message that is being sent.
	 *@param string $tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string $emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function sendVMail($senderJabberId, $vmailId, $tokboxRecipients = null, $emailRecipients = null, $text = null) {
		$method = "POST";
		$url = "/vmail/send";
		$paramList = array();
		$paramList['vmailId'] = $vmailId;
		if($tokboxRecipients !== null) $paramList['tokboxRecipients'] = $tokboxRecipients;
		if($emailRecipients !== null) $paramList['emailRecipients'] = $emailRecipients;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Forward a video mail to either TokBox contacts or a list of e-mail contacts.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $vmailId VmailId of the recorded message that is being sent.
	 *@param string $tokboxRecipients Comma separated list of TokBox Jabber IDs who will receive the VMail.
	 *@param string $emailRecipients Comma separated list of valid email addresses who will receive the VMail.
	 *@param jid $senderJabberId Jabber ID of the VMail sender.
	 *@param string $text Text of the VMail message.
	 *
	 *@return Response string to API call
	*/
	public function forwardVMail($senderJabberId, $vmailId, $tokboxRecipients = null, $emailRecipients = null, $text = null) {
		$method = "POST";
		$url = "/vmail/forward";
		$paramList = array();
		$paramList['vmailId'] = $vmailId;
		if($tokboxRecipients !== null) $paramList['tokboxRecipients'] = $tokboxRecipients;
		if($emailRecipients !== null) $paramList['emailRecipients'] = $emailRecipients;
		$paramList['senderJabberId'] = $senderJabberId;
		if($text !== null) $paramList['text'] = $text;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Removes a VMail from the feed/inbox. Returns the number of unread feed items
	 *
	 *AuthLevel: require_user
	 *
	 *@param string $messageId Message ID of the VMail being removed from the feed.
	 *@param string $type Type of message to delete from the feed. {'vmailRecv', 'vmailSent','callEvent', 'vmailPostRecv','vmailPostPublic', 'other'}
	 *
	 *@return Response string to API call
	*/
	public function deleteVMail($type, $messageId) {
		$method = "POST";
		$url = "/vmail/delete";
		$paramList = array();
		$paramList['messageId'] = $messageId;
		$paramList['type'] = $type;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Mark a VMail read. This triggers a notice to the sender. Returns the number of unread feed items
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $messageId Message ID of the VMail being marked as read
	 *
	 *@return Response string to API call
	*/
	public function markVmailRead($messageId) {
		$method = "POST";
		$url = "/vmail/markasviewed";
		$paramList = array();
		$paramList['messageId'] = $messageId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns information about a VMail which lets you access the message.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param string $messageId Message ID of the video mail being retrieved.
	 *
	 *@return Response string to API call
	*/
	public function getVMail($messageId) {
		$method = "POST";
		$url = "/vmail/getVmail";
		$paramList = array();
		$paramList['messageId'] = $messageId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns all messages sent or recieved by user containing a specific content id.
	 *
	 *AuthLevel: require_user
	 *
	 *@param string $contentId Content ID of the video mail being retrieved.
	 *
	 *@return Response string to API call
	*/
	public function getMessagesWithContent($contentId) {
		$method = "POST";
		$url = "/vmail/getMessages";
		$paramList = array();
		$paramList['contentId'] = $contentId;
		return $this->TB_Request($method, $url, $paramList);
	}

	/**
	 *Return the user's feed.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user whose feed is being requested.
	 *@param text $filter Options: {all[default], vmailSent, vmailRecv, callEvent, vmailPostPublic, vmailPostRecv, other}
	 *@param integer $start Page number of the user feed from which to start the retrieval.
	 *@param integer $count Items per page of the user feed which is being retrieved.
	 *@param text $sort What to sort the feed by
	 *@param string $locale The currently selected locale for the user
	 *@param text $dateRange Date range to filter the feed on. Should be in the format of 'DATE - DATE' where DATE is either a unix timestamp or ISO date YYY-DD-MM HH:MM:SS. Leaving out either DATE leaves it unbounded so 'DATE - ', is all feed items after DATE.
	 *
	 *@return Response string to API call
	*/
	public function getFeed($jabberId, $filter = null, $start = null, $count = null, $sort = null, $locale = null, $dateRange = null) {
		$method = "POST";
		$url = "/feed/getFeed";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		if($filter !== null) $paramList['filter'] = $filter;
		if($start !== null) $paramList['start'] = $start;
		if($count !== null) $paramList['count'] = $count;
		if($sort !== null) $paramList['sort'] = $sort;
		if($locale !== null) $paramList['locale'] = $locale;
		if($dateRange !== null) $paramList['dateRange'] = $dateRange;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns the number of unread feed items for the given user ID.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user whose feed is being requested.
	 *
	 *@return Response string to API call
	*/
	public function getFeedUnreadCount($jabberId) {
		$method = "POST";
		$url = "/feed/unreadCount";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		return $this->TB_Request($method, $url, $paramList);
	}

	/**
	 *Register a user with the system, a password will be emailed to them, and an access_secret is returned to the caller.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $firstname First Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string $lastname Last Name of the user who is being registered. The length of the name must be greater than 2 characters.
	 *@param string $email Valid email address of the user who is being registered.
	 *@param boolean $searchAllow Whether the registered user should be searchable within the TokBox environment or not. Default is true
	 *
	 *@return Response string to API call
	*/
	public function registerUser($email, $lastname, $firstname, $searchAllow = null) {
		$method = "POST";
		$url = "/users/register";
		$paramList = array();
		$paramList['firstname'] = $firstname;
		$paramList['lastname'] = $lastname;
		$paramList['email'] = $email;
		if($searchAllow !== null) $paramList['searchAllow'] = $searchAllow;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Returns - name, image, online status - information about a user.
	 *
	 *AuthLevel: require_guest
	 *
	 *@param jid $jabberId Jabber ID of the profile that is being looked up.
	 *
	 *@return Response string to API call
	*/
	public function getUserProfile($jabberId) {
		$method = "POST";
		$url = "/users/getProfile";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Create a guest user on the TokBox jabber server to make/receive calls.
	 *
	 *AuthLevel: require_partner
	 *
	 *@param string $partnerKey Partner Key of the API Developer who is trying to create the guest account
	 *
	 *@return Response string to API call
	*/
	public function createGuestUser($partnerKey) {
		$method = "POST";
		$url = "/users/createGuest";
		$paramList = array();
		$paramList['partnerKey'] = $partnerKey;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Request a contact relation with this jabberid.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user adding friends to their list.
	 *@param text $remoteJabberId Comma separated list of Jabber IDs to add to the contact list of the adding user.
	 *
	 *@return Response string to API call
	*/
	public function addContact($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/request";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Remove a user from the TokBox contact list of the given user whose ID is supplied.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user removing a contact from their TokBox contact list.
	 *@param jid $remoteJabberId Jabber ID of the user being removed from the contact list of the removing user.
	 *
	 *@return Response string to API call
	*/
	public function removeContact($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/remove";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Rejects a pending request from this jabberid. Won't remove an accepted request.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user removing a contact from their TokBox contact list.
	 *@param text $remoteJabberId Comma separated list of Jabber IDs to ignore the friend request from.
	 *
	 *@return Response string to API call
	*/
	public function rejectContact($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/reject";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}


	/**
	 *Get the relation between two users.
	 *
	 *AuthLevel: require_user
	 *
	 *@param jid $jabberId Jabber ID of the user initiating the relation check.
	 *@param jid $remoteJabberId Jabber ID against which the relation is being tested.
	 *
	 *@return Response string to API call
	*/
	public function isFriend($remoteJabberId, $jabberId) {
		$method = "POST";
		$url = "/contacts/getRelation";
		$paramList = array();
		$paramList['jabberId'] = $jabberId;
		$paramList['remoteJabberId'] = $remoteJabberId;
		return $this->TB_Request($method, $url, $paramList);
	}
}
