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

/**
 * ProfilController
 *
 * Show settings of a user and save them
 *
 * @author Thomas Layh <develop@layh.com>
 * Date: 13.03.11
 * Time: 19:50
 */
class ProfileController extends \Layh\Twitcode\Controller\BaseController {

	/**
	 * @var \Layh\Twitcode\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;

	/**
	 * @var \Layh\Twitcode\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function indexAction() {
		$this->initSidebarLogin();
	}

	/**
	 * Show all snippets you added a comment
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function commentAction() {
		$this->initSidebarLogin();

		if ($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$comments = $this->commentRepository->findCommentsByUser($loginData['user_id']);
			$this->view->assign('comments', $comments);
		} else {
			$this->flashMessageContainer->add('No User logged in!!');
		}
	}

	/**
	 * Show snippets by user
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function snippetAction() {
		$this->initSidebarLogin();

		if ($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$snippets = $this->codeRepository->findByUser($loginData['user_id']);
			$this->view->assign('snippets', $snippets);
		} else {
			$this->flashMessageContainer->add('No User logged in!!');
		}
	}

	/**
	 * Show the users favoruite snippets
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function showFavoriteSnippetsAction() {
		$this->initSidebarLogin();
	}

	/**
	 * Display settings for comment notifications
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function notificationSettingsAction() {
		$this->initSidebarLogin();

		if($this->login->isLoggedIn()) {
			$loginData = $this->login->checkSession();
			$currentUser = $this->userRepository->findByUserId($loginData['user_id']);
			if (NULL == $currentUser->getNotification()) {
				$currentUser->setNotification(FALSE);
			}
			$this->view->assign('currentUser', $currentUser);
		} else {
			$this->flashMessageContainer->add('No user logged in!!');
		}

	}

	/**
	 * Save the notification settings
	 *
	 * @param \Layh\Twitcode\Domain\Model\User $currentUser
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function updateNotificationSettingsAction(\Layh\Twitcode\Domain\Model\User $currentUser) {
		$loginData = $this->getLoginData();

		$this->userRepository->update($currentUser);

		// @todo save the notification settings

		$this->redirect('notificationSettings');

	}

}
