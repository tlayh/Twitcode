<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

/**
 * responsible for all stuff that has to do with the login
 * @scope session
 */
class Login {

	/**
	 * @var \F3\FLOW3\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * @var boolean
	 */
	protected $_isLoggedIn = false;

	/**
	 * @var \F3\Twitcode\Lib\oauth\EpiTwitter
	 */
	protected $twitterObj;

	/**
	 * @var \F3\Twitcode\Domain\Model\User
	 */
	protected $user;

	/**
	 * @var \F3\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * @var string
	 */
	protected $screenName;

	/**
	 * @var string
	 */
	protected $userId;

	/**
	 * @var string
	 */
	protected $oauthToken;

	/**
	 * @var string
	 */
	protected $oauthTokenSecret;

	// dev values
	protected $consumerKey = '2Y9OuyccVEgaEJFtVMQeyg';
	protected $consumerSecret = 'mx6gJaMbBfSDxlBuO9N74quNLVdcEjbFJAb1wQB7uQ';

	/**
	 * get the login url from oauth
	 *
	 * @return string $loginUrl
	 */
	public function getLoginUrl() {

		$this->twitterObj = new \F3\Twitcode\Lib\oauth\EpiTwitter($this->consumerKey, $this->consumerSecret);

		/** @var $requestToken \F3\Twitcode\Lib\oauth\EpiOAuthResponse */
		$requestToken = $this->twitterObj->getRequestToken();

		$this->oauthToken = $requestToken->__get('oauth_token');
		$this->oauthTokenSecret = $requestToken->__get('oauth_token_secret');

		$loginUrl = $this->twitterObj->getAuthorizeUrl($requestToken->__get('oauth_token'));

		return $loginUrl;
	}

	/**
	 * login the user
	 * should receive the information from twitter/oauth if login was successful
	 * sets cookie after successfull login, otherwise display error message
	 *
	 * @return boolean
	 */
	public function loginUser($oauthtoken) {
		$success = false;

		try {
			$this->twitterObj = new \F3\Twitcode\Lib\oauth\EpiTwitter($this->consumerKey, $this->consumerSecret);

			$this->twitterObj->setToken($this->oauthToken);
			$token = $this->twitterObj->getAccessToken(array('oauth_verifier' => $this->oauthTokenSecret));
			$this->twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
			$twitterInfo = $this->twitterObj->get('/account/verify_credentials.json');

			// if login is successful, set session data
			if($twitterInfo->__get('code') === 200) {
				$this->setSession($twitterInfo, $token->oauth_token, $token->oauth_token_secret);
				$this->_isLoggedIn = true;
				$success = true;
			}
		} catch(\Exception $e) {
			return false;
		}

		// check if user exists
		$this->checkForUser();

		return $success;
	}

	public function checkForUser() {
		$user = $this->userRepository->findByUserId(intval($this->userId));
		if(!$user) {
			$user = $this->objectManager->create('F3\Twitcode\Domain\Model\User'); /** @var $user \F3\Twitcode\Domain\Model\User */
			$user->setName($this->screenName);
			$user->setUserId($this->userId);
			$this->userRepository->add($user);
		}
	}

    public function getUserId() {
        return $this->userId;
    }

	/**
	 * logout the current user, delete the cookies
	 *
	 * @return void
	 */
	public function logoutUser() {
		$this->_isLoggedIn = false;
		$this->deleteSession();
	}

	/**
	 * check if the user is logged in
	 *
	 * @return boolean $isLoggedIn
	 */
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}

	/**
	 * sets cookies for logged in users
	 *
	 * @return void
	 */
	protected function setSession($twitterInfo, $token, $secret) {
		$this->screenName = $twitterInfo->__get('screen_name');
		$this->userId = $twitterInfo->__get('id');
		$this->oauthToken = $token;
		$this->oauthTokenSecret = $secret;
	}

	/**
	 * deletes the cookies for the logged in user during the logout process
	 *
	 * @return void
	 */
	protected function deleteSession() {
		$this->screenName = null;
		$this->userId = null;
		$this->oauthToken = null;
		$this->oauthTokenSecret = null;
	}

	/**
	 * checks the cookies and returns cookie data
	 *
	 * @return array $data
	 */
	public function checkSession() {
		$data = array();

		// get data from session
		$data['screen_name'] = $this->screenName;
		$data['user_id'] = $this->userId;
		$data['oauth_token'] = $this->oauthToken;
		$data['oauth_token_secret'] = $this->oauthTokenSecret;

		return $data;
	}

}
