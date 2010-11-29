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
 * Default controller with some basic stuff, each other controller should extend
 * this controller to get functions like the sidebar snippetes and the login data
 */
class DefaultController extends \F3\FLOW3\MVC\Controller\ActionController {

	/**
	 * @var F3\Twitcode\Domain\Model\Login
	 * @inject
	 */
	protected $login;

	/**
	 * @var \F3\Twitcode\Domain\Repository\CodetypeRepository
	 * @inject
	 */
	protected $codetypeRepository;

	/**
	 * @var \F3\Twitcode\Domain\Repository\CodeRepository
	 * @inject
	 */
	protected $codeRepository;	

    /**
	 * @var array
	 */
	protected $loginData = array();

    protected function initSidebarLogin() {
	    $this->getLoginData();
        $this->sideBarSnippets();
    }

	protected function sideBarSnippets() {
		// get latest snippets
		$latestSnippets = $this->codeRepository->findLatestSnippets(5);
		$this->view->assign('latestSnippets', $latestSnippets);
	}

	/**
	 * Initializes login data
	 *
	 * @return void
	*/
	protected function getLoginData() {
		// check login
		$loginData = array();
		if($this->login->isLoggedIn()) {
			// get Login data
			$loginData['data'] = $this->login->checkSession();
			$loginData['loggedin'] = true;
		} else {
			// get Login Url
			$loginData['loggedin'] = false;
			$loginData['url'] = $this->login->getLoginUrl();
		}
		$this->view->assign('logindata', $loginData);
	}

}

?>
