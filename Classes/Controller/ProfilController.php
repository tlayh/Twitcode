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
class ProfilController extends \Layh\Twitcode\Controller\BaseController {

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

	}

	/**
	 * Show snippets by user
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function snippetAction() {

	}

	/**
	 * Show the users favoruite snippets
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function showFavoriteSnippetsAction() {

	}

	/**
	 * Display settings for comment notifications
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function notificationSettingsAction() {

	}

	/**
	 * Save the notification settings
	 *
	 * @return void
	 * @author Thomas Layh <develop@layh.com>
	 */
	public function saveNotificationSettingsAction() {
		// @todo save the notification settings

		$this->redirect('notificationSettings');

	}

}
