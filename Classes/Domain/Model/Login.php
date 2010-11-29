<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Thomas Layh <info@twitcode.org>
 *  All rights reserved
 *
 *  This script is part of the Twitcode project. The Twitcode project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

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

	/**
	 * @var string $consumerKey for oauth, injected via Settings.yaml
	 */
	protected $consumerKey = '';

	/**
	 * @var string $consumerSecret for oauth, injected via Settings.yaml
	 */
	protected $consumerSecret = '';

	/**
	 * Inject the settings for oauth
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->consumerKey = $settings['oauth']['consumerkey'];
		$this->consumerSecret = $settings['oauth']['consumersecret'];
	}

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
