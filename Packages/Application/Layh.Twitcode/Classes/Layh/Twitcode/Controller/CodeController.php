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

use Layh\Twitcode\Domain\Model\Code;
use Layh\Twitcode\Domain\Model\Tag;
use \TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Error;
use TYPO3\Flow\Error\Message;

/**
 * Code controller for the Twitcode package
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class CodeController extends BaseController {

	/**
	 * @var \Layh\Twitcode\Domain\Repository\CommentRepository
	 * @Flow\Inject
	*/
	protected $commentRepository;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\UserRepository
	 * @Flow\Inject
	 */
	protected $userRepository;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\TagRepository
	 * @Flow\Inject
	 */
	protected $tagRepository;

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
	 * @var \EpiTwitter
	 */
	protected $twitterObj;


	/**
	 * index action
	 *
	 * @return void
	 */
	public function indexAction() {

		$this->login->setSettings($this->settings);
		$this->initSidebarLogin();
		$snippets = $this->codeRepository->findAll()->toArray();

		$this->view->assign('snippets', $snippets);
	}

	/**
	 * logout, delete the session
	 * @return void
	 */
	public function logoutAction() {
		$this->login->logoutUser();
		$this->redirect('index');
	}

    /**
	 * login action after returning from twitter
	 *
	 * @return void
	 */
	public function loginAction() {

			// check if the user really comes back from twitter and if the oauth token is set
		try {
			$oauthToken = $this->request->getArgument('oauth_token');
		    $oauthVerifier = $this->request->getArgument('oauth_verifier');
		} catch (\Exception $e) {
			$oauthToken = NULL;
		    $oauthVerifier = NULL;
		}

			// verify login
		$this->login->setSettings($this->settings);
		$successfullyLogin = $this->login->loginUser($oauthToken, $oauthVerifier);

		if ($successfullyLogin) {
			$this->flashMessageContainer->addMessage(new Message(
				'Congratulations!! Looks like you still remember your twitter account. Your login was successful!!'
			));
		} else {
			$this->flashMessageContainer->addMessage(new Message(
				'Login failed!! Sometimes OAuth is complicated so please be patient and try again.'
			));
		}

		$this->redirect('index');
	}

	/**
	 * display snippet with
	 *
	 * @Flow\IgnoreValidation(argumentName="code")
	 * @param Code $code
	 * @return void
	 */
	public function showAction(Code $code) {

		$this->login->setSettings($this->settings);

		$this->initSidebarLogin();

		// check if code belongs to current user
		if($code->getUser()->getUserId() === $this->login->getUserId()) {
			$this->view->assign('editable', TRUE);
		} else {
			$this->view->assign('editable', FALSE);
		}

		if($this->login->isLoggedIn()) {
			$this->view->assign('loggedin', TRUE);
			$this->view->assign('user', $this->login->getUser());
		} else {
			$this->view->assign('loogedin', FALSE);
		}

		$comments = $this->commentRepository->findByCode($code);
		$this->view->assign('comments', $comments);
		$this->view->assign('code', $code);
	}

	/**
	 * edit a snippet
	 *
	 * @param Code $code
	 * @return void
	 */
	public function editAction(Code $code) {
		$this->initSidebarLogin();

	    	// check for login
		if ($this->login->isLoggedIn()) {
			// assign codetypes and snippet
			$codeTypes = $this->codetypeRepository->findAll();
			$this->view->assign('codetypes', $codeTypes);
		    $this->view->assign('code', $code);
		} else {
			$this->flashMessageContainer->addMessage(new Message('Well, if you want to edit something, it really should belong to you. So please login first.'));
			$this->redirect('index');
		}
	}

	/**
	 * update an edited snippet
	 *
	 * @param Code $code
	 * @param string $tags
	 */
	public function updateAction(Code $code, $tags) {

		$tagArray = explode(',', $tags);

		$code->removeAllTags();

			// create tag objects and check if they exist
		foreach ($tagArray as $tag) {

				// remove whitespaces
			$tag = trim($tag);
			$tagObject = $this->tagRepository->findOneByTitle($tag);

				// if tag does not yet exist
			if($tagObject === NULL) {
				$tagObject = new Tag(trim($tag));
			}

			$code->addTag($tagObject);

		}
		$this->codeRepository->update($code);
		$this->flashMessageContainer->addMessage(new Message('Congratulations!! Your snippet was changed.'));
	    $this->redirect('show', 'Code', 'Layh.Twitcode', array('code'=>$code));
	}


	/**
	 * show snippet form
	 *
	 * @param Code $code
	 * @Flow\IgnoreValidation("\Layh\Twitcode\Domain\Model\Code $code")
	 * @return void
	 */
	public function createAction(Code $code=NULL) {
		$this->initSidebarLogin();

		// check for login
		if($this->login->isLoggedIn()) {
			// assign codetypes
			$codeTypes = $this->codetypeRepository->findAll();

			$this->view->assign('codetypes', $codeTypes);
		    $this->view->assign('code', $code);
		} else {
			$this->flashMessageContainer->addMessage(new Message(
				'Wouldn\'t it be nice if people would know that this is your snippet? So please login first before creating a snippet!!'
			));
			$this->redirect('index');
		}
	}


	/**
	 * save a new snippet
	 *
	 * @param Code $code
	 * @param string $tags
	 *
	 * @return void
	 */
	public function saveAction(Code $code, $tags) {

			// check for login
		$this->getLoginData();
		if ($this->login->isLoggedIn()) {
			// get current user
			$data = $this->login->checkSession();
			$user = $this->userRepository->findByUserId(intval($data['user_id']));

			$code->setUser($user);

			// set empty shortUrl
			$code->setShortUrl('');

			$tagArray = explode(',', $tags);
			foreach ($tagArray as $tagTitle) {

					// remove whitespaces
				$tagTitle = trim($tagTitle);

					// check if tag already exists
				$tagObject = $this->tagRepository->findOneByTitle($tagTitle);
				if ($tagObject === NULL) {
					$tagObject = new Tag($tagTitle);
				}

				$code->addTag($tagObject);
			}

				// get modified date
			$dateTime = new \DateTime();
			$code->setModified($dateTime);

				// get uid and set +1
			$uid = $this->codeRepository->findNextHighestUidRecord(); /** @var Code */
			$code->setUid(($uid->getUid()+1));

				// persist snippet
			$this->codeRepository->add($code);

				// twitter snippet if checkbox is NOT checked
			if( $this->request->getArgument('twitter') != 1) {
				$this->twitterSnippet($code, $data);
			}

				// add success message
			$this->flashMessageContainer->addMessage(new Message(
				'Code snippet added successfully'
			));

		} else {
				// add error message
			$this->flashMessageContainer->addMessage(new Message(
				'Looks like for some reason you got here without being logged in. Please login first.'
			));
		}

			// redirect to showSnippetAction
		$this->redirect('show', 'Code', 'Layh.Twitcode', array('code'=>$code));
	}


	public function twitterAction(Code $code) {
		$this->getLoginData();
		if ($this->login->isLoggedIn()) {
				// get current user data
			$data = $this->login->checkSession();
		    $this->twitterSnippet($code, $data);
		} else {
				// add error message
			$this->flashMessageContainer->addMessage(new Message(
				'Looks like for some reason you got here without being logged in. Please login first.'
			));
		}

	    $this->redirect('show', 'Code', 'Layh.Twitcode', array('code'=>$code));
	}

	/**
	 * generate short url via bit.ly and send snippet to twitter
	 *
	 * @param Code $code
	 * @param array $data
	 * @return void
	 */
	protected function twitterSnippet(Code $code, $data) {

			// build message text
		$comment = $code->getDescription();

			// build short url
		$url = 'http://'.$this->baseUrl.'/show/';
		$url .= $code->getUid().'/'.str_replace(' ', '-', $code->getLabel());

			// use bit.ly to shorten url
		$urlToShorten = 'http://api.bit.ly/v3/shorten?login=twitcodeorg&apiKey=R_9cde3d48cc497d36ddaa84bb41278b48&longUrl='.$url.'&format=txt';
		$handle = fopen($urlToShorten, 'r');
		$shortenUrl = fgets($handle);

			// save the short url to the model
		$code->setShortUrl($shortenUrl);
		$this->codeRepository->update($code);

			// shorten the status text for twitter
		$statusText = $shortenUrl.' - '.$comment;
		if(strlen($statusText) > 140) {
			$statusText = substr($statusText, 0 , 136).'...';
		}

			// twitter snippet
		$this->twitterObj = new \EpiTwitter($this->consumerKey, $this->consumerSecret);
		$this->twitterObj->setToken($data['oauth_token']);
		$token = $this->twitterObj->getAccessToken(array('oauth_verifier' => $data['oauth_token_secret']));
		$this->twitterObj->setToken($data['oauth_token'], $data['oauth_token_secret']);
		$resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
		if($resp->__get('code') === 200) {
			$this->flashMessageContainer->addMessage(new Message('Code snippet twittered successfully'));
		} else {
			$this->flashMessageContainer->addMessage(new Message('Twittering code snippet failed'));
		}
	}

	/**
	 * delete a snippet
	 *
	 * @param Code $code
	 */
	public function deleteAction(Code $code) {
		$this->codeRepository->remove($code);
		$this->flashMessageContainer->addMessage(new Message('Snippet deleted successfully'));
	    $this->redirect('index');
	}

	/**
	 * Override getErrorFlashMessage to present nice flash error messages.
	 *
	 * @return string
	 */
	protected function getErrorFlashMessage() {
		switch ($this->actionMethodName) {
			case 'saveAction' :
				return new Error('Could not save the snippet. Please fill out all required fields.');
			default :
				return parent::getErrorFlashMessage();
		}
	}

}
