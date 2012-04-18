<?php
/*
 * Your use of these APIs are governed by the TokBox Platform Terms of Service located at http://www.tokbox.com/legal/termsofservice
 */

class NotLoggedInException extends Exception {
    public function __construct($message) {
        parent::__construct($message, 401);
    }
}

class MalformedXmlException extends Exception {
	public function __construct($message) {
		parent::__construct($message);
	}
}
