<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 */

class TokBoxVMail {
	//ID of the specific message in which this instance was sent
	private $vmailBatchId;

	//ID of the VMail
	private $vmailId;

	//Location of the VMail Image URL
	private $vmailImgUrl;

	//Location of the VMail FLV URL
	private $vmailFlvUrl;

	//ID of the message in which this VMail was sent.
	//Should be used for interacting with the recorder
	private $vmailMessageId;

	//Array of the recipients
	private $vmailRecipients;

	//Array of the senders of a VMail
	private $vmailSenders;

	//Text associated with the VMail
	private $vmailText;

	//Timestamp of when the VMail was read
	private $vmailTimeRead;

	//Timestamp of when the VMail was sent
	private $vmailTimeSent;

	//Type of message that the VMail is
	private $vmailType;

	public function __construct($vmailId) {
		$this->vmailId = $vmailId;
	}

	public function getVmailBatchId() { return $this->vmailBatchId; }
	public function getVmailId() { return $this->vmailId; }
	public function getVmailImgUrl() { return $this->vmailImgUrl; }
	public function getVmailFlvUrl() { return $this->vmailFlvUrl; }
	public function getVmailMessageId() { return $this->vmailMessageId; }
	public function getVmailRecipients() { return $this->vmailRecipients; }
	public function getVmailSenders() { return $this->vmailSenders; }
	public function getVmailText() { return $this->vmailText; }
	public function getVmailTimeRead() { return $this->vmailTimeRead; }
	public function getVmailTimeSent() { return $this->vmailTimeSent; }
	public function getVmailType() { return $this->vmailType; }

	public function setVmailBatchId($vmailBatchId) { $this->vmailBatchId = $vmailBatchId; }
	public function setVmailImgUrl($imgUrl) { $this->vmailImgUrl = $imgUrl; }
	public function setVmailFlvUrl($flvUrl) { $this->vmailFlvUrl = $flvUrl; }
	public function setVmailMessageId($messageId) { $this->vmailMessageId = $messageId; }

	public function setVmailRecipients($recipients) {
		if(!isset($this->vmailRecipients)) {
			$this->vmailRecipients = array();
		}

		$this->vmailRecipients = $recipients;
	}

	public function setVmailSenders($senders) {
		if(!isset($this->vmailSenders)) {
			$this->vmailSenders = array();
		}

		$this->vmailSenders = $senders;
	}


	public function setVmailText($text) { $this->vmailText = $text; }
	public function setVmailTimeRead($timeRead) { $this->vmailTimeRead = $timeRead; }
	public function setVmailTimeSent($timeSent) { $this->vmailTimeSent = $timeSent; }
	public function setVmailType($type) { $this->vmailType = $type; }
}

class TokBoxVMailUser {
	//Full Name of this user associated with this VMail
	private $fullName;

	//Defines whether this user is the sendr of this VMail
	private $isSender;

	//Timestamp of the read time of the message
	private $timeRead;

	//User ID of this user associated with this VMail
	private $userId;

	public function __construct($userId, $fullName, $isSender, $timeRead = null) {
		$this->userId = $userId;
		$this->fullName = $fullName;
		$this->timeRead = $timeRead;
		$this->isSender = $isSender;
	}

	public function getFullName() { return $this->fullName; }
	public function getIsSender() { return $this->isSender; }
	public function getTimeRead() { return $this->timeRead; }
	public function getUserId() { return $this->userId; }
}
