<?php
declare(ENCODING = 'utf-8');
namespace F3\Twitcode\Controller;

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
 * Standard controller for the Twitcode package 
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class StandardController extends \F3\Twitcode\Controller\DefaultController {

	/**
	 * @var \F3\Twitcode\Domain\Repository\BookRepository
	 * @inject
	*/
	protected $bookRepository;

	// dev values
	protected $consumerKey = '2Y9OuyccVEgaEJFtVMQeyg';
	protected $consumerSecret = 'mx6gJaMbBfSDxlBuO9N74quNLVdcEjbFJAb1wQB7uQ';

	/**
	 * @var \F3\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;


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
		$oauthToken = $this->request->getArgument('oauth_token');

		$succesfullLogin = $this->login->loginUser($oauthToken);

		if($succesfullLogin) {
			$this->flashMessageContainer->add('Your login was successful!!');
		} else {
			$this->flashMessageContainer->add('Login failed!! Sometimes OAuth is a bitch so please be patient and try again.');
		}

		$this->redirect('');
	}

	/*
	 * index action
	 * 
	 * @return void
	 */
	public function indexAction() {
		$this->initSidebarLogin();

		$snippets = $this->codeRepository->findAll()->toArray();

		$this->view->assign('snippets', $snippets);
	}

	/**
	 * display snippet with short url
	 *
	 * @dontvalidate $code
	 * @return void
	 */
	public function showAction(\F3\Twitcode\Domain\Model\Code $code) {
		$this->initSidebarLogin();

		// check if code belongs to current user
		if($code->getUser()->getUserId() === $this->login->getUserId()) {
			$this->view->assign('editable', true);
		} else {
			$this->view->assign('editable', false);
		}

		$this->view->assign('code', $code);
	}

	/**
	 * edit a snippet
	 *
	 * @return void
	 */
	public function editAction(\F3\Twitcode\Domain\Model\Code $code) {
		$this->initSidebarLogin();

	    // check for login
		if($this->login->isLoggedIn()) {
			// assign codetypes and snippet
			$codeTypes = $this->codetypeRepository->findAll();
			$this->view->assign('codetypes', $codeTypes);
		    $this->view->assign('code', $code);
		} else {
			$this->flashMessageContainer->add('Please login first');
			$this->redirect('index');
		}
	}

	/**
	 * update an edited snippet
	 *
	 * @param \F3\Twitcode\Domain\Model\Code $code
	 */
	public function updateAction(\F3\Twitcode\Domain\Model\Code $code) {
		$this->codeRepository->update($code);
	    $this->redirect('show', 'Standard', 'Twitcode', array('code'=>$code));
	}

	/**
	 * show snippet form
	 *
	 * @return void
	 */
	public function createAction() {
		$this->initSidebarLogin();
		
		// check for login
		if($this->login->isLoggedIn()) {
			// assign codetypes
			$codeTypes = $this->codetypeRepository->findAll();
			$this->view->assign('codetypes', $codeTypes);
		} else {
			$this->flashMessageContainer->add('Please login first');
			$this->redirect('index');
		}
	}

	/**
	 * save a new snippet
	 *
	 * @param \F3\Twitcode\Domain\Model\Code $code
	 *
	 * @return void
	 */
	public function saveAction(\F3\Twitcode\Domain\Model\Code $code) {
		$message = '';

		// check for login
		$this->getLoginData();
		if($this->login->isLoggedIn()) {
			// get current user
			$data = $this->login->checkSession();
			$user = $this->userRepository->findByUserId(intval($data['user_id']));

			$code->setUser($user);

			// get modified date
			$dateTime = new \DateTime();
			$code->setModified($dateTime);

			// get uid and set +1
			$uid = $this->codeRepository->findNextHighestUidRecord(); /** @var F3\Twitcode\Domain\Model\Code */
			$code->setUid(($uid->getUid()+1));

			// persist snippet
			$this->codeRepository->add($code);

			// twitter snippet if checkbox is NOT checked
			if($this->request->getArgument('twitter') != 1) {

				// build message text
				$comment = $code->getComment();

				// build short url
				$url = 'http://flow.twitcode.org/show/';
				$url .= $code->getUid().'/'.str_replace(' ', '-', $code->getLabel());

				// use bit.ly to shorten url
				$urlToShorten = 'http://api.bit.ly/v3/shorten?login=twitcodeorg&apiKey=R_9cde3d48cc497d36ddaa84bb41278b48&longUrl='.$url.'&format=txt';
				$handle = fopen($urlToShorten, 'r');
				$shortenUrl = fgets($handle);

				$statusText = $shortenUrl.' - '.$comment;
				if(strlen($statusText) > 140) {
					$statusText = substr($statusText, 0 , 136).'...';
				}

				// twitter snippet
				$this->twitterObj = new \F3\Twitcode\Lib\oauth\EpiTwitter($this->consumerKey, $this->consumerSecret);
				$this->twitterObj->setToken($data['oauth_token']);
				$token = $this->twitterObj->getAccessToken(array('oauth_verifier' => $data['oauth_token_secret']));
				$this->twitterObj->setToken($data['oauth_token'], $data['oauth_token_secret']);
				$resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
				if($resp->__get('code') === 200) {
					$this->flashMessageContainer->add('Codesnippet twittered successfully');
				} else {
					$this->flashMessageContainer->add('Twittering codesnippet failed');
				}

			}
			// redirect to showSnippetAction
			$this->flashMessageContainer->add('Codesnippet added successfully');

		} else {
			$this->flashMessageContainer->add('Please login first');
		}

		$this->redirect('show', 'Standard', 'Twitcode', array('code'=>$code));
	}

}

?>
