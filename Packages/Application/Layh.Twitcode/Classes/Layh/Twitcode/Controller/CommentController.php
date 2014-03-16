<?php
namespace Layh\Twitcode\Controller;

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
use TYPO3\Flow\Error\Message;

/**
 * Discussion controller for the Twitcode package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class CommentController extends BaseController {

	/**
	 * @var \Layh\Twitcode\Domain\Repository\CommentRepository
	 * @Flow\Inject
	 */
	protected $commentRepository;

	/**
	 * @var \Twitter\Api\TwitterOAuth
	 */
	protected $twitterObj;

	/**
	 * @var string $consumerKey for oauth, injected via Settings.yaml
	 */
	protected $consumerKey = '';

	/**
	 * @var string $consumerSecret for oauth, injected via Settings.yaml
	 */
	protected $consumerSecret = '';

	/**
	 * @var string $baseUrl used for bit.ly
	 */
	protected $baseUrl;

	/**
	 * Inject the settings for oauth
	 *
	 * @param array $settings
	 * @return void
	 */
	public function injectSettings(array $settings) {

		$this->settings = $settings;

		if (isset($settings['oauth']['consumerkey'])) {
			$this->consumerKey = $settings['oauth']['consumerkey'];
		}
		if (isset($settings['oauth']['consumersecret'])) {
			$this->consumerSecret = $settings['oauth']['consumersecret'];
		}
		if (isset($settings['bitly']['url'])) {
			$this->baseUrl = $settings['bitly']['url'];
		}
	}

	/**
	 * Save the discussion
	 *
	 * @param \Layh\Twitcode\Domain\Model\Comment $comment
	 * @param \Layh\Twitcode\Domain\Model\Code    $code
	 * @return void
	 */
	public function saveAction(\Layh\Twitcode\Domain\Model\Comment $comment, \Layh\Twitcode\Domain\Model\Code $code) {

			// add code and user to discussion and add the discussion to the repository
		$comment->setCode($code);
		$comment->setUser($this->login->getUser());
		$this->commentRepository->add($comment);

		$this->flashMessageContainer->addMessage(new Message('Comment saved!!'));

			// check if the code owner wants to have a notification
		$codeOwnerUser = $code->getUser();
		if ($codeOwnerUser->getNotification() && $this->request->getArgument('notifiyCodeOwner') == 1) {
			$this->sendCommentNotification($codeOwnerUser, $code);
		}

		$this->redirect('show', 'Code', NULL, array('code' => $code));
	}

	/**
	 * Send a comment notification
	 *
	 * @param \Layh\Twitcode\Domain\Model\User $codeOwnerUser
	 * @param \Layh\Twitcode\Domain\Model\Code $code
	 */
	public function sendCommentNotification(\Layh\Twitcode\Domain\Model\User $codeOwnerUser, \Layh\Twitcode\Domain\Model\Code $code) {

			// logged in user
		$loggedInUserData = $this->login->checkSession();

		// build full url
		$url = 'http://' . $this->baseUrl . '/show/';
		$url .= $code->getUid() . '/' . str_replace(' ', '-', $code->getLabel());

/*
		$shortUrl = $code->getShortUrl();
		if (!$shortUrl) {

				// build full url
			$url = 'http://'.$this->baseUrl.'/show/';
			$url .= $code->getUid().'/'.str_replace(' ', '-', $code->getLabel());

				// use bit.ly to shorten url
			$urlToShorten = 'http://api.bit.ly/v3/shorten?login=twitcodeorg&apiKey=R_9cde3d48cc497d36ddaa84bb41278b48&longUrl='.$url.'&format=txt';
			$handle = fopen($urlToShorten, 'r');
			$shortenUrl = fgets($handle);

				// save the shortened url
			$code->setShortUrl($shortenUrl);
			$this->codeRepository->update($code);
			$shortUrl = $shortenUrl;
		}
*/

			// build twitter message
		$message = '@' . $codeOwnerUser->getName() . ' I just commented on your snippet @ ' . $url;

			// tweet notification
		$this->twitterObj = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $loggedInUserData['oauth_token'], $loggedInUserData['oauth_token_secret']);
		$resp = $this->twitterObj->post('/statuses/update', array('status' => $message));

		if (isset($resp->errors)) {
			$this->flashMessageContainer->addMessage(new Message('Notification failed.'));
		} else {
			$this->flashMessageContainer->addMessage(new Message('Notification send.'));
		}
	}


}
