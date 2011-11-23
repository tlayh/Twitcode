<?php
namespace Layh\Twitcode\Domain\Model;

require_once('/var/www/vhosts/twitcode.org/httpdocs/Packages/Applications/Layh.Twitcode/Resources/Private/Lib/oauth/EpiTwitter.php');

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
	 * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * @var boolean
	 */
	protected $_isLoggedIn = false;

	/**
	 * @var \Layh\Twitcode\Lib\oauth\EpiTwitter
	 */
	protected $twitterObj;

	/**
	 * @var \Layh\Twitcode\Domain\Model\User
	 */
	protected $user;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\UserRepository
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
	protected $userImg;

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
	 * Set the settings for oauth
	 *
	 * @param array $settings
	 * @return void
	 */
	public function setSettings(array $settings) {
		$this->consumerKey = $settings['oauth']['consumerkey'];
		$this->consumerSecret = $settings['oauth']['consumersecret'];
	}

	/**
	 * get the login url from oauth
	 *
	 * @return string $loginUrl
	 */
	public function getLoginUrl() {

		$this->twitterObj = new \EpiTwitter($this->consumerKey, $this->consumerSecret);

			/* @var $requestToken EpiOAuthResponse */
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
	 * @param string $oauthtoken
	 * @param string $oauthVerifier
	 * @return boolean
	 */
	public function loginUser($oauthtoken, $oauthVerifier) {
		$success = false;

		try {
			$this->twitterObj = new \EpiTwitter($this->consumerKey, $this->consumerSecret);
			$this->twitterObj->useSSL(true);

			$this->twitterObj->setToken($this->oauthToken);
			$token = $this->twitterObj->getAccessToken(array('oauth_verifier' => $oauthVerifier));
			$this->twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);

			$twitterInfo = $this->twitterObj->get_accountVerify_credentials();

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

	/**
	 * check if the current user already exists
	 *
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function checkForUser() {
		$user = $this->userRepository->findByUserId(intval($this->userId));
		if(!$user) {
			$user = new \Layh\Twitcode\Domain\Model\User();
			$user->setName($this->screenName);
			$user->setUserId($this->userId);
			$this->userRepository->add($user);
		} else {

				// check if the username is the same
				// @todo: check if the username is the same

			$this->user = $user;
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
	 * @param mixed $twitterInfo
	 * @param string $token
	 * @param string $secret
	 * @return void
	 */
	protected function setSession($twitterInfo, $token, $secret) {
		$this->screenName = $twitterInfo->__get('screen_name');
		$this->userId = $twitterInfo->__get('id');
		$this->userImg = $twitterInfo->__get('profile_image_url');
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
		$data['user_img'] = $this->userImg;
		$data['oauth_token'] = $this->oauthToken;
		$data['oauth_token_secret'] = $this->oauthTokenSecret;

		return $data;
	}

	/**
	 * @param \Layh\Twitcode\Domain\Model\User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return \Layh\Twitcode\Domain\Model\User
	 */
	public function getUser() {
		return $this->user;
	}

}
