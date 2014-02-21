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

use \TYPO3\Flow\Annotations as Flow;

/**
 * ProfilController
 *
 * Show settings of a user and save them
 */
class ProfileController extends \Layh\Twitcode\Controller\BaseController {

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
	 * @return void
	 */
	public function indexAction() {
		$this->initSidebarLogin();
	}

	/**
	 * Show all snippets you added a comment
	 *
	 * @return void
	 */
	public function commentAction() {
		$this->initSidebarLogin();

		if ($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$comments = $this->commentRepository->findCommentsByUser($loginData['user_id']);
			$commentCollection = $this->buildCommentCollection($comments);
			$this->view->assign('commentCollection', $commentCollection);
		} else {
			$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('No user logged in!!'));
			$this->redirect('index', 'Code');
		}
	}

	/**
	 * Show snippets by user
	 *
	 * @return void
	 */
	public function snippetAction() {
		$this->initSidebarLogin();

		if ($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$snippets = $this->codeRepository->findByUser($loginData['user_id']);
			$this->view->assign('snippets', $snippets);
		} else {
			$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('No user logged in!!'));
			$this->redirect('index', 'Code');
		}
	}

	/**
	 * Show the users favorite snippets
	 *
	 * @return void
	 */
	public function showFavoriteSnippetsAction() {
		$this->initSidebarLogin();
	}

	public function flattrSettingsAction() {
		$this->initSidebarLogin();

		if ($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$currentUser = $this->userRepository->findByUserId($loginData['user_id']);
			$this->view->assign('currentUser', $currentUser);
		} else {
			$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('No user logged in!!'));
			$this->redirect('index', 'Code');
		}
	}

	public function updateFlattrSettingsAction(\Layh\Twitcode\Domain\Model\User $currentUser) {
		$this->userRepository->update($currentUser);

		$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('Flattr-Id updated!'));
		$this->redirect('flattrSettings');
	}

	/**
	 * Display settings for comment notifications
	 *
	 * @return void
	 */
	public function notificationSettingsAction() {
		$this->initSidebarLogin();

		if($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$currentUser = $this->userRepository->findByUserId($loginData['user_id']);
			$this->view->assign('currentUser', $currentUser);
		} else {
			$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('No user logged in!!'));
			$this->redirect('index', 'Code');
		}

	}

	/**
	 * Save the notification settings
	 *
	 * @param \Layh\Twitcode\Domain\Model\User $currentUser
	 * @return void
	 */
	public function updateNotificationSettingsAction(\Layh\Twitcode\Domain\Model\User $currentUser) {
		$this->userRepository->update($currentUser);

		$this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Message('Notification settings updated!'));
		$this->redirect('notificationSettings');
	}

	/**
	 * Build a comment collection
	 *
	 * @param mixed $comments
	 * @return mixed $comments
	 */
	private function buildCommentCollection($comments) {

		$commentCollection = array();

		/** @var $comment \Layh\Twitcode\Domain\Model\Comment */
		foreach ($comments as $comment) {
			$commentCollection[$comment->getCode()->getUid()]['codeLabel'] = $comment->getCode()->getLabel();
			$commentCollection[$comment->getCode()->getUid()]['code'] = $comment->getCode();
			$commentCollection[$comment->getCode()->getUid()]['commentTime'] = $comment->getModified();
		}

		return $commentCollection;
	}

}
