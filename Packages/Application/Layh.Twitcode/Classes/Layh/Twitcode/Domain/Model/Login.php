<?php
namespace Layh\Twitcode\Domain\Model;

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

use Twitter\Api\TwitterOAuth;
use \TYPO3\Flow\Annotations as Flow;

/**
 * responsible for all stuff that has to do with the login
 * @Flow\Scope("session")
 */
class Login {

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

	/**
	 * @var boolean
	 */
	protected $_isLoggedIn = FALSE;

	/**
	 * @var TwitterOAuth
	 */
	protected $twitterOAuth;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\UserRepository
	 * @Flow\Inject
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
	 * @var string $callback the callback URL for the request token
	 */
	protected $callback;

	/**
	 * Set the settings for oauth
	 *
	 * @param array $settings
	 * @return void
	 */
	public function setSettings(array $settings) {
		$this->consumerKey = $settings['oauth']['consumerkey'];
		$this->consumerSecret = $settings['oauth']['consumersecret'];
		$this->callback = $settings['oauth']['callback'];

	}

	/**
	 * get the login url from oauth
	 *
	 * @return string $loginUrl
	 */
	public function getLoginUrl() {

		$this->twitterOAuth = new TwitterOAuth($this->consumerKey, $this->consumerSecret);

		$requestToken = $this->twitterOAuth->getRequestToken($this->callback);

		$this->oauthToken = $requestToken['oauth_token'];
		$this->oauthTokenSecret = $requestToken['oauth_token_secret'];

		$loginUrl = $this->twitterOAuth->getAuthorizeUrl($this->oauthToken);

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

		try {
			$this->twitterOAuth = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $oauthtoken, $oauthVerifier);
			$tokenCredentials = $this->twitterOAuth->getAccessToken($oauthVerifier);

			$this->twitterOAuth = new TwitterOAuth(
					$this->consumerKey, $this->consumerSecret,
					$tokenCredentials['oauth_token'], $tokenCredentials['oauth_token_secret']);

			$twitterInfo = $this->twitterOAuth->get('account/verify_credentials');

			if (isset($twitterInfo->errors)) {
				return false;
			}

			$this->setSession($twitterInfo, $tokenCredentials['oauth_token'], $tokenCredentials['oauth_token_secret']);
			$this->_isLoggedIn = true;
			$success = true;

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
			$user = new User();
			$user->setName($this->screenName);
			$user->setUserId($this->userId);
			$this->userRepository->add($user);
		} else {

				// check if the username is the same
				// todo: check if the username is the same

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
		$this->screenName = $twitterInfo->screen_name;
		$this->userId = $twitterInfo->id;
		$this->userImg = $twitterInfo->profile_image_url;
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
	 * @param User $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

}
